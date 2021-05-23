from tqdm import tqdm

import os
import numpy as np
import soundfile as sf

import torch
import torch.nn as nn
from torch.utils import data

from model_RawNet2 import RawNet2
#from parser import get_args

#from parser import *

import argparse

def str2bool(v):
    if isinstance(v, bool):
       return v
    if v.lower() in ('yes', 'true', 't', 'y', '1'):
        return True
    elif v.lower() in ('no', 'false', 'f', 'n', '0'):
        return False
    else:
        raise argparse.ArgumentTypeError('Boolean value expected.')


def get_args():
    parser = argparse.ArgumentParser()
    #dir
    parser.add_argument('-name', type = str, default = 'rawnet2_vox2')
    parser.add_argument('-save_dir', type = str, default = 'C:/xampp/htdocs/SpeakerRecognition/python_scripts/embeddings/')
    parser.add_argument('-DB', type = str, default = 'C:/xampp/htdocs/SpeakerRecognition/python_scripts/')
    parser.add_argument('-DB_vox2', type = str, default = '/media/ai/GAMMA/VoxCeleb/voxceleb2/')
    parser.add_argument('-dev_wav', type = str, default = 'dev_wav/')
    parser.add_argument('-val_wav', type = str, default = 'single_user/')
    parser.add_argument('-eval_wav', type = str, default = 'single_user/')
    
    #hyper-params
    parser.add_argument('-bs', type = int, default = 100)
    parser.add_argument('-lr', type = float, default = 0.001)
    parser.add_argument('-nb_samp', type = int, default = 59049)
    parser.add_argument('-window_size', type = int, default = 11810)
    
    parser.add_argument('-wd', type = float, default = 0.0001)
    parser.add_argument('-epoch', type = int, default = 30)
    parser.add_argument('-optimizer', type = str, default = 'Adam')
    parser.add_argument('-nb_worker', type = int, default = 8)
    parser.add_argument('-temp', type = float, default = .5)
    parser.add_argument('-seed', type = int, default = 1234) 
    parser.add_argument('-nb_val_trial', type = int, default = 40000) 
    parser.add_argument('-lr_decay', type = str, default = 'keras')
    parser.add_argument('-load_model_dir', type = str, default = 'C:/xampp/htdocs/SpeakerRecognition/python_scripts/out/rawnet2_vox2/models/model_3.pt')
    parser.add_argument('-load_model_opt_dir', type = str, default = 'C:/xampp/htdocs/SpeakerRecognition/python_scripts/out/rawnet2_vox2/models/opt_3.pt')
    parser.add_argument('-load_model_epoch', type = int, default = 3)

    #DNN args
    parser.add_argument('-m_first_conv', type = int, default = 251)
    parser.add_argument('-m_in_channels', type = int, default = 1)
    parser.add_argument('-m_filts', type = list, default = [128, [128,128], [128,256], [256,256]])
    parser.add_argument('-m_blocks', type = list, default = [2, 4])
    parser.add_argument('-m_nb_fc_att_node', type = list, default = [1])
    parser.add_argument('-m_nb_fc_node', type = int, default = 1024)
    parser.add_argument('-m_gru_node', type = int, default = 1024)
    parser.add_argument('-m_nb_gru_layer', type = int, default = 1)
    parser.add_argument('-m_nb_samp', type = int, default = 59049)
    
    #flag
    parser.add_argument('-amsgrad', type = str2bool, nargs='?', const=True, default = True)
    parser.add_argument('-make_val_trial', type = str2bool, nargs='?', const=True, default = True)
    parser.add_argument('-debug', type = str2bool, nargs='?', const=True, default = False)
    parser.add_argument('-comet_disable', type = str2bool, nargs='?', const=True, default = False)
    parser.add_argument('-save_best_only', type = str2bool, nargs='?', const=True, default = False)
    parser.add_argument('-do_lr_decay', type = str2bool, nargs='?', const=True, default = True)
    parser.add_argument('-mg', type = str2bool, nargs='?', const=True, default = False)
    parser.add_argument('-load_model', type = str2bool, nargs='?', const=True, default = True)
    parser.add_argument('-reproducible', type = str2bool, nargs='?', const=True, default = True)

    args = parser.parse_args()
    args.model = {}
    for k, v in vars(args).items():
        if k[:2] == 'm_':
            print(k, v)
            args.model[k[2:]] = v
    return args

#optioal
# from sklearn.metrics import roc_curve
# from scipy.optimize import brentq
# from scipy.interpolate import interp1d

def cos_sim(a,b):
    return np.dot(a,b) / (np.linalg.norm(a) * np.linalg.norm(b))

def get_utt_list(src_dir):
    '''
    Designed for VoxCeleb
    '''
    l_utt = []
    for path, dirs, files in os.walk(src_dir):
        # #base = '/'.join(path.split('/')[-2:])+'/'
        # base = '/'.join(path.split('/')[-2:])+'/'
        # print(base)
        for file in files:
            if file[-3:] != 'wav':
                continue
            # l_utt.append(base+file)
            l_utt.append(file)
            
    return l_utt


class TA_Dataset_VoxCeleb2(data.Dataset):
	def __init__(self, list_IDs, base_dir, nb_samp = 0, window_size = 0, labels = {}, cut = True, return_label = True, norm_scale = True):
		'''
		self.list_IDs	: list of strings (each string: utt key)
		self.labels		: dictionary (key: utt key, value: label integer)
		self.nb_samp	: integer, the number of timesteps for each mini-batch
		cut				: (boolean) adjust utterance duration for mini-batch construction
		return_label	: (boolean) 
		norm_scale		: (boolean) normalize scale alike SincNet github repo
		'''
		self.list_IDs = list_IDs
		self.window_size = window_size
		self.nb_samp = nb_samp
		self.base_dir = base_dir
		self.labels = labels
		self.cut = cut
		self.return_label = return_label
		self.norm_scale = norm_scale
		if self.cut and self.nb_samp == 0: raise ValueError('when adjusting utterance length, "nb_samp" should be input')

	def __len__(self):
		return len(self.list_IDs)

	def __getitem__(self, index):
		ID = self.list_IDs[index]
		try:
			X, _ = sf.read(self.base_dir+ID) 
			X = X.astype(np.float64)
		except:
			raise ValueError('%s'%ID)

		if self.norm_scale:
			X = self._normalize_scale(X).astype(np.float32)
		X = X.reshape(1,-1)

		list_X = []
		nb_time = X.shape[1]
		if nb_time < self.nb_samp:
			nb_dup = int(self.nb_samp / nb_time) + 1
			list_X.append(np.tile(X, (1, nb_dup))[:, :self.nb_samp][0])
		elif nb_time > self.nb_samp:
			step = self.nb_samp - self.window_size
			iteration = int( (nb_time - self.window_size) / step ) + 1
			for i in range(iteration):
				if i == 0:
					list_X.append(X[:, :self.nb_samp][0])
				elif i < iteration - 1:
					list_X.append(X[:, i*step : i*step + self.nb_samp][0])
				else:
					list_X.append(X[:, -self.nb_samp:][0])
		else :
			list_X.append(X[0])

		if not self.return_label:
			return list_X
		y = self.labels[ID.split('/')[0]]
		return list_X, y 

	def _normalize_scale(self, x):
		'''
		Normalize sample scale alike SincNet.
		'''
		return x/np.max(np.abs(x))


def main():
    #parse arguments
    args = get_args()

    #make experiment reproducible if specified
    if args.reproducible:
        torch.manual_seed(args.seed)
        np.random.seed(args.seed)
        torch.backends.cudnn.deterministic = True
        torch.backends.cudnn.benchmark = False

    #device setting
    cuda = torch.cuda.is_available()
    device = torch.device('cuda' if cuda else 'cpu')
    print('Device: {}'.format(device))

    #get utt_lists & define labels
    #l_dev = sorted(get_utt_list(args.DB_vox2 + args.dev_wav))

    #d_label_vox2 = get_label_dic_Voxceleb(l_dev)
    #args.model['nb_classes'] = len(list(d_label_vox2.keys()))
    args.model['nb_classes'] = 6112
    # l_eval = sorted(get_utt_list(args.DB + args.val_wav))


    #define dataset generators


    #set save directory
    save_dir = args.save_dir + args.name + '/'
    if not os.path.exists(save_dir):
        os.makedirs(save_dir)
        
    '''
    #log experiment parameters to local and comet_ml server
    f_params = open(save_dir + 'f_params_eval.txt', 'w')
    for k, v in sorted(vars(args).items()):
        print(k, v)
        f_params.write('{}:\t{}\n'.format(k, v))
    for k, v in sorted(args.model.items()):
        print(k, v)
        f_params.write('{}:\t{}\n'.format(k, v))
    f_params.close()
    '''
    

    epoch = 0
    if args.load_model: epoch = args.load_model_epoch
        
    l_utt = sorted(get_utt_list(args.DB + args.val_wav))
    
    # with open(args.DB + 'val_trial.txt', 'r') as f:
    #     l_trial = f.readlines()

    TA_evalset = TA_Dataset_VoxCeleb2(list_IDs = l_utt,
        return_label = False,
        window_size = args.window_size, # 20% of nb_samp
        nb_samp = args.nb_samp, 
        base_dir = args.DB+args.val_wav)
    db_gen = data.DataLoader(TA_evalset,
        batch_size = 1, 
        shuffle = False,
        drop_last = False,
        num_workers = args.nb_worker)

        #define model
    if bool(args.mg):
        model_1gpu = RawNet2(args.model)
        if args.load_model: model_1gpu.load_state_dict(torch.load(args.load_model_dir))
        nb_params = sum([param.view(-1).size()[0] for param in model_1gpu.parameters()])
        model = nn.DataParallel(model_1gpu).to(device)
    else:
        model = RawNet2(args.model).to(device)
        if args.load_model: model.load_state_dict(torch.load(args.load_model_dir,  map_location = torch.device('cpu')))
        nb_params = sum([param.view(-1).size()[0] for param in model.parameters()])
    if not args.load_model: model.apply(init_weights)
    print('nb_params: {}'.format(nb_params))

    model.eval()
    with torch.set_grad_enabled(False):
        #1st, extract speaker embeddings.
        l_embeddings = []
        with tqdm(total = len(db_gen), ncols = 70) as pbar:
            for m_batch in db_gen:
                l_code = []
                for batch in m_batch:
                    batch = batch.to(device)
                    code = model(x = batch, is_test=True)
                    l_code.extend(code.cpu().numpy())
                l_embeddings.append(np.mean(l_code, axis=0))
                pbar.update(1)
        d_embeddings = {}

        if not len(l_utt) == len(l_embeddings):
            print(len(l_utt), len(l_embeddings))
            exit()
        for k, v in zip(l_utt, l_embeddings):
            print(v)
            f_embd = open("../embeddings/" + k.replace(".wav",".txt"), 'w', buffering = 1)
            d_embeddings[k] = v

            # f_embd.write(str(v))

            for line in v:
                f_embd.write(str(line) + "\n")

            f_embd.close()

    #     # 2nd, calculate EER
    #     y_score = [] # score for each sample
    #     y = [] # label for each sample 
        
    #     for line in l_trial:
    #         trg, utt_a, utt_b = line.strip().split(' ')
    #         y.append(int(trg))
    #         y_score.append(cos_sim(d_embeddings[utt_a], d_embeddings[utt_b]))
        
    #     print(y)
    #     print(y_score)

    #     fpr, tpr, _ = roc_curve(y, y_score, pos_label=1)
    #     eer = brentq(lambda x: 1. - x - interp1d(fpr, tpr)(x), 0., 1.)
     
    # print('TA_EER: %f'%float(eer))



if __name__ == '__main__':
    main()