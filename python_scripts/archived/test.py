import soundfile as sf
f = sf.SoundFile('../audio_files/test.wav')

print(' samples: {} <br>'. format(len(f)))
print(' sample rate: {} <br>'.format(f.samplerate))
print(' seconds: {} <br>'.format(len(f) / f.samplerate))
