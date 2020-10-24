import subprocess
from os import listdir
from os.path import isfile, join


def get_files_from_directory(path):
    """Get all files from directory"""
    return [f for f in listdir(path) if isfile(join(path, f))]


def run_eLite_HTS(script, path_file, file, resource_type, output_format, mode):
    """Parameters:
    script - script of eLite-HTS
    path_file - path of the input file
    file - name of input file
    resource_type - texts / textgrids
    output_format - hts, dls, textgrid_hts
    mode - train / run"""
    command = 'perl ' + script + ' ' + path_file + file + ' ' + resource_type + ' ' + output_format + ' ' + mode
    output = subprocess.check_output(command, shell=True)  # run the command
    return output


def save_output(path, name_file, output):
    with open(path+name_file, 'w+') as f:
        output = str(output)
        output = output.replace('\\n', '\n')
        f.write(output)


if __name__ == '__main__':
    # paths need to be update with your own
    path = '/home/macaire/Bureau/M2_NLP/Software_Project/Corpus/SIWIS/SiwisFrenchSpeechSynthesisDatabase/text/part2/' # path of siwis corpus
    path_output = '/home/macaire/Bureau/M2_NLP/Software_Project/Results/siwiss_results/general/part2/' # path to store the results
    files = get_files_from_directory(path)
    # output = run_eLite_HTS('/home/macaire/Bureau/M2_NlP/Software_Project/Script/eLite-HTS_script.pl', path, 'neut_book_s05_0328.txt', 'texts', 'hts',
    #                        'run')
    # save_output(path_output,'neut_book_s05_0328.txt', str(output))
    for i in files:
        try:
            output = run_eLite_HTS('/home/macaire/Bureau/Software_Project/Script/eLite-HTS_script.pl', path, i, 'texts', 'hts', 'run')
            save_output(path_output,i, str(output))
        except:
            print(i)

