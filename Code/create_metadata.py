from os import listdir
from os.path import join, isfile


def get_files_from_directory(path):
    """Get all files from directory"""
    return [f for f in listdir(path) if isfile(join(path, f))]


def init_dict():
    emotion_dict = {'Neutral':0, 'Amused':1, 'Angry':2, 'Disgusted':3, 'Sleepy':4}
    speaker_dict = {'f': 0, 'm': 1}
    language_dict = {'french': 0, 'english': 1}


def get_phonemes_english(dictionary, path_english, name):
    files_english_1 = get_files_from_directory(path_english+'/'+name+'/')
    for i in files_english_1:
        with open(path_english+'/'+name+'/'+i) as f:
            lines = f.readlines()
            lines = [i[:-3] for i in lines]
            for i in lines:
                if i not in dictionary.keys():
                    dictionary[i] = len(dictionary)
    return dictionary


def global_dict_english(path_english):
    phonemes_dict = {}
    get_phonemes_english(phonemes_dict, path_english, 'bea')
    get_phonemes_english(phonemes_dict, path_english, 'sam')
    get_phonemes_english(phonemes_dict, path_english, 'josh')
    get_phonemes_english(phonemes_dict, path_english, 'jenie')
    return phonemes_dict


def global_dict_french(path_french, dictionary):
    files = get_files_from_directory(path_french)
    for i in files:
        with open(path_french + i) as f:
            lines = f.readlines()
            lines = [i[:-1] for i in lines]
            for i in lines:
                if i not in dictionary.keys():
                    dictionary[i] = len(dictionary)
    dictionary.pop('[')
    dictionary.pop('')
    dictionary['G'] = 52
    return dictionary



if __name__ == '__main__':
    dict = global_dict_english('/home/macaire/Bureau/M2_NLP/Software_Project/multilingual-text-to-speech-system-software-project/Results/EmoV-DB_sorted/phonemes/all/')
    print(global_dict_french('/home/macaire/Bureau/M2_NLP/Software_Project/multilingual-text-to-speech-system-software-project/Results/SIWIS French/central_phoneme/', dict))