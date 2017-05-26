
.. _contributing to stanlee:

Contributing to Stanlee
=======================

We're passionate about helping Stanlee users make the jump to contributing members of the community, so there are many ways you can help stanlee development:

+ Report bugs and request features in our `ticket tracker`_. Please read `reporting bugs`_, below, for the details on how you sould do this.
+ Submit patches or pull requests for new and/or fixed behavior. Please read `submitting patches`_, below, for details on how to submit a patch. If you're looking for an easy way to start contributing to Stanlee have a look at the easy-pickings tickets.
+ Join the `Stanlee Mailing List`_ and share your ideas for how to improve Stanlee. We're always open to suggestions.
+ Improve Stanlee documentation by reading, writing new articles or correcting existing ones. You can find more information on how we do this at `writing documentation`_.
+ Translate Stanlee into your local language, by joining our translation team. Read more on `translating stanlee`_.

That's all you need to know if you'd like to join the Stanlee development community. The rest of this document describes the details of how our community works and how it handles bugs, mailing lists, and all the other details of Stanlee development.

.. _reporting bugs:

Reporting bugs
--------------

Well-written bug reports are incredibly helpful. However, there's a certain amount of overhead involved in working with any bug tracking system so your help in keeping our ticket tracker as useful as possible is appreciated. In particular:

+ Check that someone hasn't already filed the bug or feature request by searching in the `Ticket Tracker`_.
+ If you are not sure if what you are seeing is a bug, ask first on the `Stanlee Mailing List`_ or the `Stanlee IRC Channel`_.
+ Don't use the ticket system to ask support questions. Use the `Stanlee Mailing List`_ or the `Stanlee IRC Channel`_ for that.
+ Write complete, reproducible, specific bug reports. You must include a clear, concise description of the problem, and a set of instructions for replicating it. Add as much debug information as you can: code snippets, test cases, error messages, screenshots, etc. A nice small test case is the best way to report a bug, as it gives us an easy way to confirm the bug quickly.

First of all, `Signup at GitHub`_, is very quick and easy.

Next, gather all the information you have about the bug you are going to report (*everything* could be useful). Go to the `New Issue Form`_ in our `Ticket Tracker`_ and fill in a descriptive title for the ticket and the content of the bug you are reporting.

If you need to attach a piece of code, error or patch, please use the Gist_. The gist is a special service from github to add pieces of code in a tiny repository publicly available. Add the code you need in a `New public Gist`_, and then paste the resultant link in the bug report.

.. _submitting patches:

Submitting patches
------------------

A patch is a structured file that consists of a list of differences between one set of files and another. All additions and deletions of **contributed code** to Stanlee are done through patches.

A patch can be sent to Stanlee developers through the `Ticket Tracker`_ and the Gist_. Add the patch code in a `New public Gist`_ and then `open a new ticket`_ describing the patch you made. Remember to add the link to the public gist so it can be referenced and checked out by developers. Also, you will need to `open an account`_ on github to do all of this.

If you want to know more about creating, applying and submitting patches, see `working with patches`_.

If you want to install the development version of Stanlee to start reviewing, understanding and improving the code, read `downloading the development version`_.

You might also want to start `understanding stanlee source code`_.

.. _submitting pull requests:

Submitting pull requests
------------------------

A pull request is a special service from code repositories that enable developers to manage contribution activities more easily. It allows a developer to clone or fork a repository of code, make changes to it and then request the addition of this modified code to the upstream developers of Stanlee.

If you want to contribute to Stanlee using this method, you will have to fork Stanlee on github, download the forked code to your computer, make the changes you need to make in order to correct the bug or implement a new feature, push your changes and finally request your code to be pulled to Stanlee main repository. You will need to `open an account`_ on github to do all of this.

If you want detailed information how to make a pull request, please read the `pull request guide`_.

You might also want to start reading `understanding stanlee source code`_.

There's also more information about `working with git`_ available.

.. _writing documentation:

Writing documentation
---------------------

We place a high importance on consistency, readability, simplicity and portability of documentation. After all, great documentation is what makes great projects great.

Documentation changes generally come in two forms:

+ **General improvements:** typo corrections, error fixes and better explanations through clearer writing and more examples.
+ **New features:** documentation of features that have been added to the core since the last release.

Stanlee's documentation uses the `Sphinx Documentation System`_, which in turn is based on reStructuredText (reST or RST). The basic idea is that lightly-formatted plain-text documentation is transformed into HTML, WIKI, and MAN pages with minor efforts. We always modify the documentation in the development stage/branch.

If you want to obtain a copy of the development version of Stanlee to start reviewing, understanding and improving documentation, read `downloading the development version`_.

To actually build the documentation locally, you'll currently need to install sphinx and other applications. Read the document on `installing software dependencies`_ for the complete list of dependencies and how to install them.

Once downloaded, you will find the documentation sources (rest format) on ``documentation/rest``. You will also notice other folders (described below). These are autogenerated documentation formats, **DO NOT** edit them directly, edit the sources instead.

+ ``documentation/html``:  Contains the generated HTML using sphinx. These HTML pages are updated using the rest sources located at ``documentation/rest`` through the command ``make gen-html``.
+ ``documentation/man``:  Contains the rest source to produce a manual page (man) through the ``rst2man`` application. ``make gen-man`` on the top level directory will produce the file ``documentation/man/stanlee.1``.
+ ``documentation/githubwiki``:  This directory contains a git submodule for the GitHub Wiki. The generated wiki format is copied here and uploaded to GitHub to keep all documentation in sync. ``make gen-wiki`` will update the files on this folder using the rest sources on ``documentation/rest``.
+ ``documentation/googlewiki``:  This directory also contains a git submodule but for the Google Code Wiki. The generated wiki format is copied here and uploaded to Google Code to keep all documentation in sync. ``make gen-wiki`` will update the files on this folder using the rest sources on ``documentation/rest``.

If you want to modify or add new documents, feel free to inspect the ``documentation/rest`` folder. If you add a new rest file in the sources folder, don't worry about it not being referenced on the index or the table of contents, this is done automatically at build time.

To get started contributing, you'll want to read the `reStructuredText basics`_. After that, you'll want to read `understanding stanlee source code`_ to know how and where you are going to make the changes.

If you want your contributions to be added on official documentation, please read `submitting patches`_ and `submitting pull requests`_.

.. _translating stanlee:

Translating Stanlee
-------------------

Translations are contributed by Stanlee users everywhere. The translation work is coordinated at Transifex_ and the `Stanlee Mailing List`_.

If you find an incorrect translation or want to discuss specific translations, go to the translation team page for that language. If you would like to help out with translating or add a language that isn't yet translated, here's what to do:

#. Join the `Stanlee Mailing List`_ and introduce yourself, explaining what you want to do.

#. `Signup at Transifex`_ and visit the `Stanlee Project Page`_.

#. On the `Translation Teams Page`_, choose the language team you want to work with, or – in case the language team doesn't exist yet – request a new team by clicking on the "Request a new team" button and select the appropriate language.

Then, click the "Join this Team" button to become a member of this team. Every team has at least one coordinator who is responsible to review your membership request. You can of course also contact the team coordinator to clarify procedural problems and handle the actual translation process.

Once you are a member of a team choose the translation resource you want to update on the team page. For example the "core" resource refers to the main translation catalogue.

Generating POT templates
++++++++++++++++++++++++

A POT template is a file which contains all translatable strings contained in the PHP sources; these strings are translated in a PO file for each language. You will need Stanlee POT template (which is located in ``locale/pot/stanlee/messages.pot``) to update the PO file of the language you are working on.

To update or generate Stanlee POT template, enter the following command in the root folder of Stanlee development version (see `downloading the development version`_)::

	make gen-pot

Updating PO files from POT template
+++++++++++++++++++++++++++++++++++

To update all PO files in the *locale* folder using Stanlee POT template (``locale/pot/stanlee/messages.pot``), enter the following command in the root folder of Stanlee development version (see `downloading the development version`_)::

	make gen-po

Generate a new PO with Stanlee translation assistant
++++++++++++++++++++++++++++++++++++++++++++++++++++

If you want to create a new PO for a new language, you can use the Stanlee translation assistant which is located at ``tools/maint/l10n-newpo.sh``. Enter the following command, passing as argument a valid l10n code according to the language you wish to translate to::

	./tools/maint/l10n-newpo.sh [L10N CODE]

For example::

	./tools/maint/l10n-newpo.sh en_GB

Basically, it will copy the POT template to the proper folder, depending on what l10n code you specify; then, it will start asking you to translate each string to generate the new PO.

.. _pull request guide:

Contributing code through pull requests
=======================================

The Stanlee development model follows the standard *GitHub model* for contributions: `fork a project`_, clone it to your local computer, hack on it there, push your finished changes to your forked repository, and send a *Pull Request* back upstream to the project. If you're already familiar with this process, then congratulations! You're done here, `get hacking`_!

The GitHub guide for the standard fork_/clone/push/`pull request`_ model of contributing is a great place to start, but we'll cover all the basics here, too, as well as various contribution scenarios (fixing bugs, adding features, updating documentation, etc.).

.. _getting started:

Getting Started
---------------

First, you'll need a GitHub account. If you don't have one, go and `Signup at GitHub`_. After that, you'll need to `provide your SSH key`_ to authorize your computer to make pushes.

You need to download and install Git and then configure it. You might want to take a look at our `working with git`_.

Now you're ready to grab the Stanlee repository. Hit the `Stanlee Repository Page`_ and click the *Fork* button. This will create a complete copy of the Stanlee repository within your GitHub account.

Now you've got your own fork. This is your own personal copy of the Stanlee source tree, and you can do anything you want with it. Next, let's clone your fork of Stanlee. Just switch to a directory where you want to keep your repository and do this, replacing ``[USERNAME]`` with your actual GitHub username::

	git clone --branch development git@github.com:[USERNAME]/stanlee.git

Congratulations! You now have your very own Stanlee repository. Now you'll want to make sure that you can always pull down changes from the upstream canonical repository. To do so, do this::

	cd stanlee
	git remote add upstream git://github.com/LuisAlejandro/stanlee.git 
	git pull upstream development

Anytime you want to merge in the latest changes from the upstream repository, just issue the command ``git pull upstream development`` to pull the latest from the development branch of the upstream repository and you'll be good to go. You should also push it up to your fork::

	git push origin development

.. _submitting the pull request:

Submitting the pull request
---------------------------

After you have pushed the changes to your fork, go to your fork page on github and hit the *pull request* button. After that, you are presented with a preview page where you can enter a title and optional description, see exactly what commits will be included when the pull request is sent, and also see who the pull request will be sent to (Stanlee upstream repository).

More information on `pull request`_.