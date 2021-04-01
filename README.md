# FEUERWASSER CloneItem (frwssr_cloneitem)
Field type to clone an item in a multi-item region in [PerchCMS](https://grabaperch.com/).

## Installation

1. Download zip archive and extract locally.
2. Create a `frwssr_cloneitem` folder in the `/perch/addons/fieldtypes/` folder of your perch install.
3. Copy the files `frwssr_cloneitem.class.php`, `index.php`, and `init.js` to the `/perch/addons/fieldtypes/frwssr_cloneitem` folder.

## Usage
In a perch template, you can use this field type as follows:
```html
<perch:content id="clone" type="frwssr_cloneitem" suppress>
```

### Attributes
- *buttontext* - Customize the text on the button. Defaults to “✌️ Clone item ⚠️” (—the emoji trying to signify the *danger zone* character of the button.)
- *buttonbg* - Customize the background of the button. Defaults to `slategray`. You might get fancy with something like `buttonbg="linear-gradient(to top right, teal, tomato)"`, too. Impress your Perch users!
- *renamefield* - Pass the ID of a heading field (or similar) to alter said field in the clone.
- *renamepostfix* - Customize the text appended to the field passed with `renamefield`. Will do nothing, if `renamefield` is not present.  
Defaults to “ (Copy)”.
- *unsetfields* - Pass the IDs of one or more fields to be unset—and the (optional) desired unset values—to have them unset/altered. If no value is provided, field will be set to an empty string.  
Be aware, that commas (`,`) and the pipe character (`|`) cannot be part of an unset value. You may use encoded HTML characters, though need to have the ` html` attribute on the outputting field for it to render as desired.   
Pattern: `id|,id|unset value`.  
Example: `unsetfields="slug,date,islive|❌"`.

### Example
```html
<perch:content id="clone" type="frwssr_cloneitem" buttontext="Make a copy of this awesome item" buttonbg="linear-gradient(to top right, teal, tomato)" renamefield="itemheading" renamepostfix="—copy" unsetfields="slug,date,islive|❌" suppress>
```

### Notes
- Use `suppress` on the `frwssr_cloneitem` field to make sure it doesn’t show up in your website (if the same template is used to render the content).
- The occult `_title` field of the content item will get the postfix (custom or default) appended, now matter what, to let you know, you are in the newly cloned content item. Even if you do not make use of `renamefield`. Be aware, that if you do not change the value of the field, that is the basis for `_title`, in the cloned item, the `_title` will change back to its original value, as soon as you save the new content item.
- If you are creating a slug (or something similar) of any field in the template, make sure to unset that field using the `unsetfields` attribute, in order to not end up with duplicate slugs.
- This fieldtype was developed under Perch (Standard) Version 3.1.7 on a server running PHP 7.4.x.  
**Use at own risk!**

### Acknowledgement
I want to thank fellow Percher [Jordin Brown](https://twitter.com/cognetif) for pointing me in the right direction and his enlightening feedback.


# License
This project is free, open source, and GPL friendly. You can use it for commercial projects, for open source projects, or for almost whatever you want, really.

# Donations
This is free software, but it took some time to develop. If you use it, please let me know—I live off of positive feedback…and chocolate.
If you appreciate the fieldtype and use it regularly, feel free to [buy me some sweets](https://paypal.me/nlsmlk).

# Issues
Create a GitHub Issue: https://github.com/frwssr/frwssr_cloneitem/issues or better yet become a contributor.

Developer: Nils Mielke (nils.m@feuerwasser.de, [@nilsmielke](https://twitter.com/nilsmielke)) of [FEUERWASSER](https://www.feuerwasser.de)
