# Created by Cecile MACAIRE 
# v1: 28/09/2020

import json
from os import listdir
from os.path import isfile, join


def get_files_from_directory(path):
    """Get all files from directory"""
    return [f for f in listdir(path) if isfile(join(path, f))]


def extract_data_json(path, file):
    with open(path+file) as json_file:
        data = json.load(json_file)
    return data


def extract_phonemes(data):
    # print(data['words'])
    phone = []
    for el in data['words']:
        if 'phones' in el:
            for m in el['phones']:
                phone.append(m['phone'])
    return phone


def save_phonemes(path, phoneme_file, phones):
    with open(path+phoneme_file, 'w+') as f:
        for i in phones:
            f.write(i+'\n')


if __name__ == '__main__':
    path_json = '/home/macaire/Bureau/Software_Project/Results/alignments/EmoV-DB_sorted/sam/Sleepy/'
    path_store = '/home/macaire/Bureau/Software_Project/Phonemes/EmoV-DB/sam/Sleepy/'
    files = get_files_from_directory(path_json)
    for i in files:
        name_store_file = i[:-5]+'_phonemes.txt'
        data = extract_data_json(path_json, i)
        phones = extract_phonemes(data)
        save_phonemes(path_store, name_store_file, phones)
