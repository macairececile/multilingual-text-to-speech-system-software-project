# Authors : Ludivine ROBERT - Cécile MACAIRE
# Created in October the 8th
# Update version in November the 27th

from os import listdir
from os.path import join, isfile
import os
import json


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


def dict_phonemes_all(path_french):
    """Create dictionary of french phonemes from french corpus
    Input: path fo french corpus (with phonemes extracted),
    dictionary which contains english phonemes
    Output: final dictionary"""
    dictionary = {}
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
def get_text_english(path_folders, dic_emotions, dic_speakers):
    """Create metadata for english corpus with text, emotion id,
    speaker id and language id for each file of the corpus
    Input: path english text corpus, emotions dictionary, and speakers dictionary
    Output: dictionary with metadata"""
    metadata_english = {}
    language = 0
    dir_speakers = get_directories(path_folders)
    for i in dir_speakers:
        speaker_name = i.split('/')[-1]
        dir_emotion = get_directories(i)
        for j in dir_emotion:
            emotion = j.split('/')[-1]
            dir_files = get_files_from_directory(j)
            for k in dir_files:
                text = []
                with open(j + '/' + k) as f:
                    data = json.loads(f.read())
                    for l, m in data.items():
                        if l == 'transcript':
                            text.append(m)
                    text=''.join(text)
                path_file = '/' + speaker_name + '/' + emotion + '/' + k.split('/')[-1][:-5] + '.wav'
                metadata_english[path_file] = [text, dic_speakers.get(speaker_name),
                                               dic_emotions.get(emotion),
                                               language]
    return metadata_english


def get_text_english_bis(path_folders):
    metadata_english = {}
    files = get_files_from_directory(path_folders)
    for k in files:
        text = []
        with open(path_folders+ '/' + k) as f:
            data = json.loads(f.read())
            for l, m in data.items():
                if l == 'transcript':
                    text.append(m)
                text=''.join(text)
        path_file = k.split('/')[-1][:-5] + '.wav'
        metadata_english[path_file] = [text, 5,0,0]
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
    with open(path_save_metadata + 'preprocessing_file_2.txt', 'w+') as f:
        for key, val in metadata.items():
            phonemes = str(val[0]).strip('[]')
            line = key + '|' + phonemes + '|' + str(val[1]) + '|' + str(val[2]) + '|' + str(val[3]) + '\n'
            f.write(line)


# ------- Main process ------- #
def process(path_french_phonemes, path_english, path_englishljspeech_phonemes, path_save_data):
    dict_phonemes = dict_phonemes_all(path_french_phonemes)
    emotion_dic, speaker_dic = definition_dict()
    m_en = get_text_english(path_english, emotion_dic, speaker_dic)
    m_fr = get_data_french(path_french_phonemes, dict_phonemes)
    m_en2 = get_text_english_bis(path_englishljspeech_phonemes)
    metadata(m_en, m_fr,m_en2, path_save_data)


if __name__ == '__main__':
    path_french = '/Users/Ludivine/Documents/Université/Master TAL/Année 2020-2021/Semestre 9/UE905 EC2 Projet logiciel/multilingual-text-to-speech-system-software-project/Results/SIWIS_French/phonemes/'
    path_english = '/Users/Ludivine/Documents/Université/Master TAL/Année 2020-2021/Semestre 9/UE905 EC2 Projet logiciel/multilingual-text-to-speech-system-software-project/Results/EmoV-DB_sorted/general/'
    path_english_ljspeech = '/Users/Ludivine/Documents/Université/Master TAL/Année 2020-2021/Semestre 9/UE905 EC2 Projet logiciel/multilingual-text-to-speech-system-software-project/Results/LJSpeech/general/'
    process(path_french, path_english, path_english_ljspeech, '/Users/Ludivine/Documents/Université/Master TAL/Année 2020-2021/Semestre 9/UE905 EC2 Projet logiciel/multilingual-text-to-speech-system-software-project/Results/')
