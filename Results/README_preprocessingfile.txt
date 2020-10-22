This README contains a short description of the preprocessing file.
Explanation of the file and description of the identifiers.

The data are from the English EmoV-DB corpus, English LJSpeech corpus and SIWIS French corpus.


The file is structured on the following form: <filepath wav>|<text>|<speakerid>|<emotions>|<languageid>

*<text> is an array of integers that map to the phonemes

*<speakerid> refers to the speaker
	0=sam
	1=bea
	2=josh
	3=jenie
	4=fr
	5=en

*<emotions> refers to the emotion
	0=neutral
	1=amused
	2=angry
	3=gisguted
	4=sleepy

*<languageid> refers to the language
	0=english
	1=french