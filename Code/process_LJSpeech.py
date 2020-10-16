import json
import subprocess
from os import listdir
from os.path import join, isfile
import os
import csv


def get_files_from_directory(path):
    """Get all files from directory"""
    return [f for f in listdir(path) if isfile(join(path, f))]


def read_csv(file, path_save):
    with open(file, newline='') as csvfile:
        spamreader = csv.reader(csvfile, delimiter='|')
        for row in spamreader:
            with open(path_save + row[0] + '.txt', 'w+') as f:
                if len(row) == 2:
                    f.write(row[1])
                else:
                    f.write(row[2])


def run_gentle(path_wav, path_texts, align_db_file, path_output):
    files = get_files_from_directory(path_wav)
    for i in files:
        text_file = i[:-4]+'.txt'
        command = 'python3 ' + align_db_file + ' ' + path_wav + i + ' ' + path_texts + text_file
        output = subprocess.check_output(command, shell=True)
        with open(path_output + text_file[:-4] + '.json', 'w', encoding='utf-8') as f:
            output = json.loads(output)
            json.dump(output, f, ensure_ascii=False)
            # output = str(output).replace(r'\n', '\n')
            # f.write(output)


if __name__ == '__main__':
    run_gentle('/home/macaire/Bureau/M2_NLP/Software_Project/Corpus/LJSpeech/wavs/', '/home/macaire/Bureau/M2_NLP/Software_Project/Corpus/LJSpeech/texts/', '/home/macaire/Bureau/M2_NLP/Software_Project/gentle/align.py', '/home/macaire/Bureau/M2_NLP/Software_Project/Corpus/LJSpeech/general/')
# read_csv('/home/macaire/Bureau/M2_NLP/Software_Project/Corpus/LJSpeech/metadata.csv', '/home/macaire/Bureau/M2_NLP/Software_Project/Corpus/LJSpeech/texts/')
