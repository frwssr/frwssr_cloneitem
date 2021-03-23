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
- *renamefield* - Pass the id of a heading field (or similar) to alter said field in the clone.
- *renamepostfix* - Customize the text appended to the field passed with `renamefield`. Will do nothing, if `renamefield` is not present. Defaults to “ (Copy)”.

### Example
```html
<perch:content id="clone" type="frwssr_cloneitem" buttontext="Make a copy of this awesome item" buttonbg="linear-gradient(to top right, teal, tomato)" renamefield="itemheading" renamepostfix="—copy" suppress>
```

### Notes
- Use `suppress` on the `frwssr_cloneitem` field to make sure it doesn’t show up in your website (if the same template is used to render the content).
- The occult `_title` field of the content item will get the postfix (custom or default) appended, now matter what, to make the cloned item distinguishable from the original in the item list. Even if you do not make use of `renamefield`.
- If you are creating a slug of any field in the template, the slug field in the cloned item will hold the exact same value as the original. You need to update that manually. (Unfortunately, there is no way to automate this, as you might name the slug field whatever you want.)
- This fieldtype was developed under Perch (Standard) Version 3.1.7 on a server running PHP . **Use at own risk!**

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
