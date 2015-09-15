# Xersion v0.2

<b>Folder: variables</b><br>
This folder holds all variables as JSON files.
The JSON's should be laid out as property name as the javascript variable names and the property values as variable values.
Make sure to include a property called "version" and then every other property after that should be the variable name, you can make it anything you want as long as it matches javascript requirements, and you can add as properties as you needed. 
The Name of the JSON file should be exactly the same as the folder holding the source filesets in order for it to know what vars go to which fileset.
If you add in a variable/property called "clickTag" it acts a bit differently, in the index.html file wherever you put [clickTag] it will replace that with the value you have in that column. (This is mainly for Doubleclick HTML5 Reqs)
