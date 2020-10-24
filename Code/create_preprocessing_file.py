# Authors : Ludivine ROBERT - Cécile MACAIRE
# Created in October the 8th

from os import listdir
from os.path import join, isfile
import os


# ------- Get files, directory from path ------- #
def get_files_from_directory(path):
    """Get all files from directory"""
    return [f for f in listdir(path) if isfile(join(path, f))]


def get_directories(path):
    return [f.path for f in os.scandir(path) if f.is_dir()]


# ------- Dictionaries definition (phonemes, emotions, speakers)------- #
def definition_dict():
    """Definition of two dictionaries: one for emotion, the other one for speaker id"""
    emotions_dict = {'Neutral': 0, 'Amused': 1, 'Angry': 2, 'Disgusted': 3, 'Sleepy': 4}
    speakers_dict = {'sam': 0, 'bea': 1, 'josh': 2, 'jenie': 3, 'fr': 4, 'en':5}
    return emotions_dict, speakers_dict


def get_phonemes_english(dictionary, path_english, name):
    """Get phonemes from all the files of english corpus and add them into dictionary
    Input: dictionary, path of english corpus, name of the speaker
    Output: dictionary
    """
    files_english_1 = get_files_from_directory(path_english + '/' + name + '/')
    for i in files_english_1:
        with open(path_english + '/' + name + '/' + i) as f:
            lines = f.readlines()
            lines = [i[:-3] for i in lines]
            for i in lines:
                if i not in dictionary.keys():
                    dictionary[i] = len(dictionary)
    return dictionary


def dict_phonemes_english(path_english):
    """Create the final dictionary of english phonemes"""
    phonemes_dict = {}
    get_phonemes_english(phonemes_dict, path_english, 'bea')
    get_phonemes_english(phonemes_dict, path_english, 'sam')
    get_phonemes_english(phonemes_dict, path_english, 'josh')
    get_phonemes_english(phonemes_dict, path_english, 'jenie')
    return phonemes_dict


def dict_phonemes_all(path_french, dictionary):
    """Create dictionary of french phonemes from french corpus
    Input: path fo french corpus (with phonemes extracted),
    dictionary which contains english phonemes
    Output: final dictionary"""
    files = get_files_from_directory(path_french)
    for i in files:
        with open(path_french + i) as f:
            lines = f.readlines()
            lines = [i.rstrip() for i in lines]
            for i in lines:
                if i not in dictionary.keys():
                    dictionary[i] = len(dictionary)
    dictionary.pop('[]')
    dictionary['G'] = len(dictionary)
    return dictionary


# ------- Create metadata file from data of both corpus ------- #
def get_data_english(path_folders, dic_phonemes, dic_emotions, dic_speakers):
    """Create metadata for english corpus with phonemes id, emotion id,
    speaker id and language id for each file of the corpus
    Input: path english corpus (just before the folders of speakers), phonemes dictionary,
    emotions dictionary, and speakers dictionary
    Output: dictionary with metadata"""
    metadata_english = {}
    language = 0
    dir_speakers = get_directories(path_folders)
    for i in dir_speakers:
        speaker_name = i.split('/')[-1]
        dir_emotion = get_directories(i)
        if speaker_name != 'all':
            for j in dir_emotion:
                emotion = j.split('/')[-1]
                dir_files = get_files_from_directory(j)
                for k in dir_files:
                    phonemes_id = []
                    with open(j + '/' + k) as f:
                        lines = f.readlines()
                        lines = [i[:-3] for i in lines]
                        for el in lines:
                            phonemes_id.append(dic_phonemes.get(el))
                    path_file = '/' + speaker_name + '/' + emotion + '/' + k.split('/')[-1][:-13] + '.wav'
                    metadata_english[path_file] = [phonemes_id, dic_speakers.get(speaker_name),
                                                   dic_emotions.get(emotion),
                                                   language]
    return metadata_english


def get_data_english_bis(path_folders, dic_phonemes):
    metadata_english = {}
    files = get_files_from_directory(path_folders)
    for k in files:
        phonemes_id = []
        with open(path_folders+ '/' + k) as f:
            lines = f.readlines()
            lines = [i[:-3] for i in lines]
            for el in lines:
                phonemes_id.append(dic_phonemes.get(el))
        path_file = k.split('/')[-1][:-13] + '.wav'
        metadata_english[path_file] = [phonemes_id, 5,0,0]
    return metadata_english


def get_data_french(path_folders, dic_phonemes):
    """Create metadata for french corpus with phonemes id, emotion id,
        speaker id and language id for each file of the corpus
        Input: path french corpus, phonemes dictionary
        Output: dictionary with metadata"""
    metadata_french = {}
    language = 1
    files = get_files_from_directory(path_folders)
    for i in files:
        with open(path_folders + '/' + i) as f:
            phonemes_id = []
            lines = f.readlines()
            lines = [i.rstrip() for i in lines]
            for el in lines:
                phonemes_id.append(dic_phonemes.get(el))
        path_file = '/' + i.split('/')[-1][:-4] + '.wav'
        metadata_french[path_file] = [phonemes_id, 4, 0, language]
    return metadata_french


def metadata(metadata_english, metadata_french, metadata_englishbis, path_save_metadata):
    """Create metadata file with metadata of both corpus
    Input: metadata english, metadata french, path to save the file
    Output: txt file with metadata"""
    metadata = {**metadata_english, **metadata_french, **metadata_englishbis}  # merge of metadata from both corpus
    with open(path_save_metadata + 'metadata_software_project.txt', 'w+') as f:
        for key, val in metadata.items():
            phonemes = str(val[0]).strip('[]')
            line = key + '|' + phonemes + '|' + str(val[1]) + '|' + str(val[2]) + '|' + str(val[3]) + '\n'
            f.write(line)


# ------- Main process ------- #
def process(path_english_all_phonemes, path_french_phonemes, path_english, path_englishljspeech_phonemes, path_save_data):
    dict_english = dict_phonemes_english(path_english_all_phonemes)
    dict_phonemes = dict_phonemes_all(path_french_phonemes, dict_english)
    emotion_dic, speaker_dic = definition_dict()
    m_en = get_data_english(path_english, dict_phonemes, emotion_dic, speaker_dic)
    m_fr = get_data_french(path_french_phonemes, dict_phonemes)
    m_en2 = get_data_english_bis(path_englishljspeech_phonemes, dict_phonemes)
    metadata(m_en, m_fr,m_en2, path_save_data)


if __name__ == '__main__':
    path_english_all = '/home/macaire/Bureau/M2_NLP/Software_Project/multilingual-text-to-speech-system-software-project/Results/EmoV-DB_sorted/phonemes/all/'
    path_french = '/home/macaire/Bureau/M2_NLP/Software_Project/multilingual-text-to-speech-system-software-project/Results/SIWIS_French/phonemes/'
    path_english = '/home/macaire/Bureau/M2_NLP/Software_Project/multilingual-text-to-speech-system-software-project/Results/EmoV-DB_sorted/phonemes/'
    path_english_ljspeech = '/home/macaire/Bureau/M2_NLP/Software_Project/multilingual-text-to-speech-system-software-project/Results/LJSpeech/phonemes/'
    process(path_english_all, path_french, path_english, path_english_ljspeech, '/home/macaire/')
