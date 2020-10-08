This README contains a short description of the preprocessing file.
Explanation of the file and description of the identifiers.

The data are from the English EmoV-DB corpus and SIWIS French corpus.


The file is structured on the following form: <filepath wav>|<text>|<speakerid>|<emotions>|<languageid>

*<text> is an array of integers that map to the phonemes

*<speakerid> refers to the speaker
	0=sam
	1=bea
	2=josh
	3=jenie
	4=fr

*<emotions> refers to the emotion
	0=Neutral
	1=Amused
	2=Angry
	3=Disguted
	4=Sleepy

*<languageid> refers to the language
	0=english
	1=french