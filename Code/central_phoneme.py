from os import listdir
from os.path import isfile, join
import re

def get_files_from_directory(path):
    """Get all files from directory"""
    return [f for f in listdir(path) if isfile(join(path, f))]


def save_output(path, name_file, output):
    with open(path+name_file, 'w+') as f:
        output = str(output)
        output = output.replace('\\n', '\n')
        f.write(output)


if __name__ == '__main__':
    # paths need to be update with your own
    path = '/Users/prachmeanleakhena/Desktop/Corpus/results/' # path of siwis corpus
    path_output = '/Users/prachmeanleakhena/Desktop/Corpus/central_phoneme/' # path to store the results
    files = get_files_from_directory(path)
    for i in files:
        try:
            with open('/Users/prachmeanleakhena/Desktop/Corpus/results/'+i.lower()) as f:
                string = f.readlines()

            phonemes = [re.findall(r'\-.\+', l) for l in string]
            list_of_phonemes = [item[1] for items in phonemes for item in items]
            save_output(path_output,i, str(list_of_phonemes))

        except:
            print(i)

