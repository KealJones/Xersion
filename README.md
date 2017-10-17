# Xersion v0.2

Xersion is a very young and basic tool that helps rapidly generate multiple versions of a source fileset. 
It was originaly built for rapid versioning of HTML5 Ads so you may notice a few referances to clickTag.

## Folder Structure:
**Folder**: *source/*

This folder holds the folders of the base filesets. 
Each folder have an index.html file, and any sub folder structure you'd like (ex. `/js/`, `/css/`, etc). 
In the index.html file you simply need a section that has this one line above the actual javascript file:
```html
<script src="vars.js"></script>
```

A "vars.js" file should NOT be included in the base ad. One will be generated by Xersion. 
In the index.html file you should have a `<script> var clickTag = "[clickTag]"; </script>` somewhere in the index.html.
If you add in a variable/column called "clickTag" it acts a bit differently, in the index.html file wherever you put `[clickTag]` it will replace that with the value you have in that column.

**Folder**: *variables/*

This folder holds all variables as CSV files.
The CSV's should be laid out as column headers as the variable names and the rows the variable values.
The First Column should be "Version" (with the capital) and then every column after that should be the variable name, you can make it anything you want as long as it matches javascript requirements, and you can add as many columns as you need. 
The Name of the CSV file should be exactly the same as the folder holding the source filesets in order for it to know what vars go to which fileset.
If you add in a variable/column called "clickTag" it acts a bit differently, in the index.html file wherever you put [clickTag] it will replace that with the value you have in that column.

**Folder**: *generated/*

This folder holds all the results after running: generate.php
It will be every fileset sorted by Version in folders.
Each Version folder will have have the unzipped folders for each version and a zipped version.
