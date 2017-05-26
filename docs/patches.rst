
.. _working with patches:

Working with patches
====================

.. _whats a patch:

What's a patch?
---------------

A patch is a structured file that consists of a list of differences between one set of files and another. All additions and deletions of **contributed code** to Stanlee are done through patches.

**Patches make development easier**, because instead of supplying a replacement file, possibly consisting of thousands of lines of code, the patch includes only the exact changes that were made. In effect, a patch is a list of all the changes made to a file, which can be used to re-create those changes on another copy of that file.

Here is an example of what a patch looks like::

	diff --git a/token_example/token_example.tokens.inc b/token_example/token_example.tokens.inc
	index 585dcea..b06d9d6 100644
	--- a/token_example/token_example.tokens.inc
	+++ b/token_example/token_example.tokens.inc
	@@ -13,8 +13,8 @@ function token_example_token_info() {
	   // second is the user's default text format, which is itself a 'format' token
	   // type so it can be used directly.
	
	-  // This is a comment in the original file. It will be removed when the patch is applied.
	+ // And here are lines we added when we were editing the file.
	+ // They will replace the line above when the patch is applied.
	$info['types']['format'] = array(
	     'name' => t('Text formats'),
	     'description' => t('Tokens related to text formats.'),

At the top it says which file is being affected. Changes to several files can be included in one patch. Lines added are shown with a '+', lines removed are shown with a '-', lines changed are shown as the old line being removed and the new one added.

Patch files are good because they only show the changed parts of the file. This has two advantages: it's easy to understand the change; and if other parts of the same files change between the patch being made an being used, there is no problem, the patch will still apply.

Note that there are several slight variants of the patch format. The example above is a 'unified' patch, which is now the standard.

If you want to create a patch, the first thing you will have to do is to download the latest snapshot (development stage) of Stanlee to your computer. Use git to clone the project and obtain a copy from github (you only need to do this once)::

        git clone --branch development https://github.com/LuisAlejandro/stanlee.git

If you already have cloned the code, then pull the latest changes down to ensure you're working with the latest snapshot::

	git checkout development
	git pull origin development

.. _creating a patch using diff:

Creating a patch using diff
---------------------------

#. Make a copy of the entire folder that contains the code::

	cp -r stanlee/ stanlee.changes/

#. Start making the necessary changes to implement the feature or bug correction you have in mind::

	cd stanlee.changes/
	# Edit file in your favorite editor:
	<your_editor_here> [filename]
	# Make more edits in other files and save theme:
	<your_editor_here> [other-filename]

#. To create the patch "my_changes.patch", issue the following command at folder containing the original and the changed folder::

	diff -Naur stanlee.orig stanlee.changes > my_changes.patch

.. _creating a patch using git:

Creating a patch using git
--------------------------

#. Create and checkout a *local branch* (with the name of your preference) for the issue/feature/bug you're working on::

	git branch [NEWBRANCH]
	git checkout [NEWBRANCH]

#. Make whatever changes are necessary to complete what you're doing::

	# Edit file in your favorite editor:
	<your_editor_here> [filename]
	# After making your edits and saving the file, confirm your changes are present:
	git status
	# Make more edits in other files and save theme:
	<your_editor_here> [other-filename]
	# Confirm changes again:
	git status

#. When you are satisfied, make one single commit with all your changes using the -a option::

	git commit -a -m "Descriptive message here."

#. Now, create your patch file. The following command will output the difference between the current branch (where your changes are) and the *development* branch that you cloned from the code repository to a file named "my_changes.patch"::

	git diff development > my_changes.patch 

.. _submitting the patch:

Submitting the patch
--------------------

.. _open an account:
.. _Signup at GitHub: https://github.com/signup/free
.. _open a new ticket:
.. _New Issue Form: https://github.com/LuisAlejandro/stanlee/issues/new
.. _Ticket Tracker: https://github.com/LuisAlejandro/stanlee/issues
.. _New public Gist: https://gist.github.com/gists/new
.. _Gist: https://gist.github.com/

A patch can be sent to Stanlee developers through the `Ticket Tracker`_ and the Gist_. Add the patch code in a `New Public Gist`_ and then `open a new ticket`_ describing the patch you made. Remember to add the link to the public gist so it can be referenced and checked out by developers. Also, you will need to `open an account`_ on github to do all of this.
