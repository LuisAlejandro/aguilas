
.. _working with git:

Working with git
================

.. _graphical summary: http://zrusin.blogspot.com/2007/09/git-cheat-sheet.html

Git is a free and open source, distributed version control system designed to handle everything from small to very large projects with speed and efficiency. If you want a list of common commands, see the `git cheat sheet`_ or it's `graphical summary`_.

For those too impatient to read, here's what you need to work with someone else's Git project::

	git clone [URL]
	git add .
	git commit -a
	git push
	git pull

Before running any command the first time, it's recommended that you skim through its manual page. Many of the commands have very useful and interesting features (that we won't list here) and sometimes there are some extra notes you might want to know. You can also get help on any Git command by doing ``git [COMMAND] -h`` or ``git help [COMMAND]``.

.. _installing git:

Installing git
--------------

If you are using Debian, Ubuntu, Canaima or other Debian derivative, you can install git easily with the following command (as an administrator user)::

	aptitude install git-core

.. _configuring git:

Configuring git
---------------

Git includes your real name and your e-mail address on every commit you make. You should add these before you start using Git::

	git config --global user.name "Your Complete Name"
	git config --global user.email you@yourdomain.example.com

``git config`` is the command to set configuration options. While you're configuring, here are some other things you might like to do::

	# colorized `git diff`, etc.
	git config --global color.ui auto

	# list all configuration options
	git config -l

.. _create new repository:

Creating a New Repository
-------------------------

Let's step through how you create and update a new project:

If you are creating a new project, you should::

	mkdir myproject; cd myproject
	git init

If you are putting an existing project under git version control, you should::

	cd myproject
	git init
	git add .
	git commit -a

``git init`` initializes the repository, ``git add .`` adds all the files under the current directory and ``git commit -a`` creates the initial import of files.

If you are downloading someone else's git project, you should do this instead::

	git clone [URL]

Whichever the case, now your tree is officially tracked by git. One nice thing to note - no matter how many subdirectories your project has, there's only one .git directory that contains all the version control information. Do some random changes to your tree now - poke around in a few files or something.

.. _making changes:

Making Changes
--------------

When you've edited some files, next thing you have to do is add them to version control. First you check what you've done::

	git diff

That's it. This is one of the more powerful commands. To get a diff with a specific commit, do::

	git diff [COMMIT]

You can obtain the commit number by listing all commits with::

	git log

Also, there is a more concise representation of changes available::

	git status

This will show the concise changes summary as well as list any files that you haven't either ignored or told Git about. In addition, it will also show at the top which branch you are in.

The status command also shows the "untracked files" that Git doesn't know what to do with. You can either include them in the registry (in the case these are new files)::

	git add .

Or delete them (in the case these are unwanted files from a build process, for example). *WARNING* this will delete all untracked files forever::

	git clean -fd

You could also list all files you want to ignore forever. These files will never be added to version control. What you need to do is create a file named ``.gitignore``, containing a list of the files, line by line.

If you made changes to a file that you want to undo, you can get back the last version you committed::

	git checkout [PATH/TO/FILE]

So after you have messed around with the project files, it's about time for us to commit our changes::

	git commit -a

There are two considerations to observe:

First, you have to specify ``-a`` if you want to commit all your files, or ``git commit [PATH/TO/FILE]`` to commit file by file.

Second, Git commits are private by default - they aren't pushed to any central server unless you specify it. We'll talk about pushing changes later, but private commits have some important benefits. For example, when you realise you left some debugging iformation in your last commit, or made a typo in the commit message, you can do ``git commit --amend`` to fix it, or even do ``git reset HEAD^`` to toss the commit away completely without affecting your files.

A few words about the commit message: it is customary to have a short commit summary as the first line of the message, because many tools just show the first line of the message. You can specify the commit message using the -m parameter (extra -m arguments will create extra paragraphs in the commit message).

.. _browsing the repository:

Browsing the repository
-----------------------

Now that we have committed some stuff, you might want to review your history::

	git log
	git blame [PATH/TO/FILE]

The ``log`` command is very powerful, it shows you the complete history of the project, commit by commit, and also extra information like authors and dates. For example, ``git log --oneline`` will show you the first few characters of each commit ID and the first line of each commit message. See the *git-log manual page* for more stuff git log can do.

The ``blame`` command is also very useful, as it identifies the author of every line of every file registered by git.

You can see the contents of a file, the listing of a directory or a commit with::

	git show [COMMIT]:[PATH/TO/FILE]
	git show [COMMIT]:[PATH/TO/DIRECTORY]
	git show -s [COMMIT]
	git show [COMMIT]

.. _branching and tagging:

Branching and Tagging
---------------------

Git marks checkpoints in history by applying a label to a commit. You can create a branch with the following commands:

	git branch [NEW] [OLD]
	git checkout [NEW]

The first command creates a branch, the second command switches your tree to the new branch. You can pass an extra argument to ``git branch`` to base your new branch on a different revision than the latest one.

Running ``git branch`` without arguments lists your branches. The \* in the output marks the current branch::

	git branch

To move your tree to some older revision, use::

	git checkout [COMMIT]

Git tags are fairly similar to Git branches, but with some extra tricks. Git tags can have a date, committer, and message that act just like the equivalents for Git commits. They can also be signed with a PGP key if you really want to stamp them with your seal of approval. This is great if you want to release a public version of your work, because you can have one place to store your release announcement and your guarantee that the code hasn't been tampered with. So, let's do it::

	git tag -a [NAME]

To list tags and to show a tag message::

	git tag -l
	git show [NAME]

.. _merging branches:

Merging branches
----------------

Let's suppose you are on branch "release", and you want to bring the changes you've made on "development" branch, then you'll have to do::

	git merge development

If changes were made on only one of the branches since the last merge, they are simply replayed on your other branch (a so-called fast-forward merge). If changes were made on both branches, they are merged intelligently (a so-called three-way merge). If the three-way merge doesn't have any merge conflicts, it makes a commit with a convenient log message (the ``--no-commit`` option disables this). If there are merge conflicts (when one ore more lines of a file being merged have different values in the previous state), ``git merge`` will report them and let you resolve them.

To resolve a conflict, you will have to look in the file being reported as conflict for the following pattern::

	<<<<<<< HEAD:file.txt
	Hello world
	=======
	Goodbye
	>>>>>>> 77976da35a11db4580b80ae27e8d65caf5208086:file.txt

which can be explained like this::

	<<<<<<<
	changes made on my branch
	=======
	changes made on the branch i'm merging
	>>>>>>>

You will have to erase manually which part are you going to leave. After editing all conflicts, you have to commit your changes::

	git commit -a

Aside from merging, sometimes you want to just pluck one commit out of a different branch. To apply the changes in revision rev and commit them to the current branch use::

	git cherry-pick [COMMIT]

.. _working with remote servers:

Working with remote servers
---------------------------

If you created your repository with one of the ``clone`` commands, Git will have already set up a remote repository for you called *origin*. If you created your repository from scratch, you will have to set it up.

To see which remote servers you have configured, you can run the ``git remote`` command. It lists the shortnames of each remote handle you've specified. If you've cloned your repository, you should at least see *origin* — that is the default name Git gives to the server you cloned from::

	git remote -v

To add a new remote Git repository as a shortname you can reference easily, run::

	git remote add [SHORTNAME] [URL]

When you cloned your repository, Git downloaded all the branches and tags in that repository, and created your master branch based on the master branch in that repository. Even though it only used the master branch, it kept copies of all the others in case you needed them. Copies of branches from a remote repository are called remote branches, and don't behave quite like the local branches you've used before.

For starters, remote branches don't show up in a normal git branch. Instead, you list remote branches with ``git branch -r``. You can log these branches, diff them and merge them, but you can't commit to them, or they would stop being copies of the branch on the remote repository. If you want to work with a remote branch, you need to create a local branch that "tracks" it, like this::

	git checkout -t origin/branch

Now, how do you download new changes from a remote repository? You fetch them with ``git fetch``. But usually you don't just want to fetch, you also want to merge any changes into your local branch::

	git pull

A pull is technically a bit different to a rebase. As always, see the relevant manual pages for details.

.. _sharing your work:

Sharing your Work
-----------------

We saw in the previous section that you can pull other people's work into your repository, but how do your changes get back out? Well, your Git repository is as good as any other repository, so you could just ask people to git pull from you the same way you git pulled from them.

This is fine as far as Git's concerned, but what if you have a slow Internet connection, or you are behind a firewall, or you like to amend your commits before letting people see them? Most people get around this by having two repositories: a private repository they work on, and a public repository for people to pull from.

So how do you get your work onto your public repository? Well, it's the opposite of ``git pull``, so you ``git push``!

When you have your project at a point that you want to share, you have to push it upstream. The command for this is simple::

	git push [REMOTE-NAME] [BRANCH-NAME]

If you want to push your master branch to your origin server, then you can run this to push your work back up to the server::

	git push origin master

This command works only if you cloned from a server to which you have write access and if nobody has pushed in the meantime. If you and someone else clone at the same time and they push upstream and then you push upstream, your push will rightly be rejected. You’ll have to pull down their work first and incorporate it into yours before you’ll be allowed to push.

.. _working with submodules:

Working with submodules
-----------------------

Git submodules allows you to attach or include an external repository inside another repository at a specific path. It basically permits to handle various "subprojects" inside one big project.

There are four main functions you will need to understand in order to work with Git submodules. In order, you will need to know how to add, make use of, remove, and update submodules.

.. _adding submodules to a git repository:

Adding Submodules to a Git Repository
+++++++++++++++++++++++++++++++++++++

Adding a submodule to a git repository is actually quite simple. For example, let's suppose we want to add support for another (fictionary) wiki: the mediawiki format wiki, on the ``documentation/mediawiki`` folder. You can do so with the following command::

	git submodule add git@github.com:mediawiki/wiki.git documentation/mediawiki

There are three main parts to this command:

+ **git submodule add**:  This simply tells Git that we are adding a submodule. This syntax will always remain the same.
+ **git@github.com:mediawiki/wiki.git**:  This is the external repository that is to be added as a submodule. The exact syntax will vary depending on the setup of the Git repository you are connecting to. You need to ensure that you have the ability to clone the given repository.
+ **documentation/mediawiki**:  This is the path where the submodule repository will be added to the main repository.

If you make ``git status``, you will notice how the supplied path was created and added to the changes to be committed. In addition, a new file called ``.gitmodules`` was created. This new file contains the details we supplied about the new submodule. Out of curiosity, if you check out the contents of that new file::

	cat .gitmodules

::

	[submodule "documentation/mediawiki"]
	path = documentation/mediawiki
	url = git@github.com:mediawiki/wiki.git

Being able to modify this file later will come in handy later.

All that is left to do now is to commit the changes and then push the commit to a remote system if necessary.

.. _using submodules:

Using Submodules
++++++++++++++++

Having submodules in a repository is great and all, but if you look inside, all you will have is an empty folder rather than the actual contents of the submodule's repository. In order to fill in the submodule's path with the files from the external repository, you must first initialize the submodules and then update them.

First, you need to initialize the submodule(s). You can do that with the following command on the root folder of the main project::

	git submodule init

Then you need to run the update in order to pull down the files::

	git submodule update

Looking in the ``documentation/mediawiki`` directory now shows a nice listing of the needed files.

.. _removing submodules:

Removing Submodules
+++++++++++++++++++

What happens if we need to remove a submodule? Maybe I made a mistake. It could also be that the design of the project has changed, and the submodules need to change with it. Unfortunately, Git does not have a built in way to remove submodules. we have to do it manually.

Sticking with the example, we'll remove the ``documentation/mediawiki`` module from Stanlee. All the instructions will be run from the working directory of the Stanlee repository. In order, we need to do the following:

+ **Remove the submodule's entry in the .gitmodules file**: Open it up in vim, or your favorite text editor, and remove the three lines relevant to the submodule being removed. In this case, these lines will be removed::

	[submodule "documentation/mediawiki"]
	path = documentation/mediawiki
	url = git@github.com:mediawiki/wiki.git

+ **Remove the submodule's entry in the .git/config file**: Open it up in vim, or your favorite text editor, and remove the two lines relevant to the submodule being removed. In this case, these lines will be removed::

	[submodule "documentation/mediawiki"]
	url = git@github.com:mediawiki/wiki.git

+ **Remove the path created for the submodule**: Run the following command to finish removing the submodule::

	git rm --cached documentation/mediawiki

.. _updating submodules:

Updating Submodules
+++++++++++++++++++

Unfortunately, like removing submodules, Git does not make it clear how to update a submodule to a later commit. Fortunately though, it's not that tough.

Initialize the repository's submodules by running ``git submodule init`` followed by ``git submodule update``::

	git submodule init
	git submodule update

Change into the submodule's directory. In this example, ``documentation/mediawiki``::

	cd documentation/mediawiki

The submodule repositories added by ``git submodule update`` are "headless". This means that they aren't on a current branch.To fix this, we simply need to switch to a branch. In this example, that would be the development branch::

	git checkout development

Next, we simply need to update the repository to ensure that we have the latest updates::

	git pull

Now switch back to the root working directory of the repository::

	cd ../..

Everything is now ready to be committed and pushed back in. If you run ``git status``, you'll notice that the path to the submodule is listed as modified. This is what you should expect to see. Simply add the path to be committed and do a commit. When you do the commit, the index will update the commit string for the submodule::

	git add .
	git commit -a

.. _git cheat sheet:

Git Cheat Sheet
===============

Setup
-----

git clone <repo>
  clone the repository specified by <repo>; this is similar to "checkout" in
  some other version control systems such as Subversion and CVS

Add colors to your ~/.gitconfig file:

  [color]
    ui = auto
  [color "branch"]
    current = yellow reverse
    local = yellow
    remote = green
  [color "diff"]
    meta = yellow bold
    frag = magenta bold
    old = red bold
    new = green bold
  [color "status"]
    added = yellow
    changed = green
    untracked = cyan

Highlight whitespace in diffs

  [color]
    ui = true
  [color "diff"]
    whitespace = red reverse
  [core]
    whitespace=fix,-indent-with-non-tab,trailing-space,cr-at-eol

Add aliases to your ~/.gitconfig file:

  [alias]
    st = status
    ci = commit
    br = branch
    co = checkout
    df = diff
    dc = diff --cached
    lg = log -p
    lol = log --graph --decorate --pretty=oneline --abbrev-commit
    lola = log --graph --decorate --pretty=oneline --abbrev-commit --all
    ls = ls-files

    # Show files ignored by git:
    ign = ls-files -o -i --exclude-standard


Configuration
-------------

git config -e [--global]
  edit the .git/config [or ~/.gitconfig] file in your $EDITOR

git config --global user.name 'John Doe'
git config --global user.email johndoe@example.com
sets your name and email for commit messages

git config branch.autosetupmerge true
  tells git-branch and git-checkout to setup new branches so that git-pull(1)
  will appropriately merge from that remote branch.  Recommended.  Without this,
  you will have to add --track to your branch command or manually merge remote
  tracking branches with "fetch" and then "merge".

git config core.autocrlf true
  This setting tells git to convert the newlines to the system's standard
  when checking out files, and to LF newlines when committing in

git config --list
  To view all options

git config apply.whitespace nowarn
  To ignore whitespace

You can add "--global" after "git config" to any of these commands to make it
apply to all git repos (writes to ~/.gitconfig).


Info
----
git reflog
  Use this to recover from *major* mess ups! It's basically a log of the
  last few actions and you might have luck and find old commits that
  have been lost by doing a complex merge.

git diff
  show a diff of the changes made since your last commit
  to diff one file: "git diff -- <filename>"
  to show a diff between staging area and HEAD: `git diff --cached`

git status
  show files added to the staging area, files with changes, and untracked files

git log
  show recent commits, most recent on top. Useful options:
  --color       with color
  --graph       with an ASCII-art commit graph on the left
  --decorate    with branch and tag names on appropriate commits
  --stat        with stats (files changed, insertions, and deletions)
  -p            with full diffs
  --author=foo  only by a certain author
  --after="MMM DD YYYY" ex. ("Jun 20 2008") only commits after a certain date
  --before="MMM DD YYYY" only commits that occur before a certain date
  --merge       only the commits involved in the current merge conflicts

git log <ref>..<ref>
  show commits between the specified range. Useful for seeing changes from
  remotes:
  git log HEAD..origin/master # after git remote update

git show <rev>
  show the changeset (diff) of a commit specified by <rev>, which can be any
  SHA1 commit ID, branch name, or tag (shows the last commit (HEAD) by default)
  also to show the contents of a file at a specific revision, use git show <rev>:<filename>
  this is similar to cat-file but much simpler syntax.

git show --name-only <rev>
  show only the names of the files that changed, no diff information.

git blame <file>
  show who authored each line in <file>

git blame <file> <rev>
  show who authored each line in <file> as of <rev> (allows blame to go back in
  time)

git gui blame
  really nice GUI interface to git blame

git whatchanged <file>
  show only the commits which affected <file> listing the most recent first
  E.g. view all changes made to a file on a branch: git whatchanged <branch> <file>  | grep commit | colrm 1 7 | xargs -I % git show % <file>
  this could be combined with git remote show <remote> to find all changes on
  all branches to a particular file.

git diff <commit> head path/to/fubar
  show the diff between a file on the current branch and potentially another
  branch

git diff --cached [<file>]
  shows diff for staged (git-add'ed) files (which includes uncommitted git
  cherry-pick'ed files)

git ls-files
  list all files in the index and under version control.

git ls-remote <remote> [HEAD]
  show the current version on the remote repo. This can be used to check whether
  a local is required by comparing the local head revision.

Adding / Deleting
-----------------

git add <file1> <file2> ...
  add <file1>, <file2>, etc... to the project

git add <dir>
  add all files under directory <dir> to the project, including subdirectories

git add .
  add all files under the current directory to the project
  *WARNING*: including untracked files.

git rm <file1> <file2> ...
  remove <file1>, <file2>, etc... from the project

git rm $(git ls-files --deleted)
  remove all deleted files from the project

git rm --cached <file1> <file2> ...
  commits absence of <file1>, <file2>, etc... from the project

Ignoring
---------

Option 1:

Edit $GIT_DIR/info/exclude. See Environment Variables below for explanation on
$GIT_DIR.

Option 2:

Add a file .gitignore to the root of your project. This file will be checked in.

Either way you need to add patterns to exclude to these files.

Staging
-------

git add <file1> <file2> ...
git stage <file1> <file2> ...
add changes in <file1>, <file2> ... to the staging area (to be included in the next commit

git add -p
git stage --patch
interactively walk through the current changes (hunks) in the working tree, and decide which changes to add to the staging area.

git add -i
git stage --interactive
interactively add files/changes to the staging area. For a simpler mode (no menu), try `git add --patch` (above)

Unstaging
---------

git reset HEAD <file1> <file2> ...
  remove the specified files from the next commit


Committing
----------

git commit <file1> <file2> ... [-m <msg>]
  commit <file1>, <file2>, etc..., optionally using commit message <msg>,
  otherwise opening your editor to let you type a commit message

git commit -a
  commit all files changed since your last commit
  (does not include new (untracked) files)

git commit -v
  commit verbosely, i.e. includes the diff of the contents being committed in
  the commit message screen

git commit --amend
  edit the commit message of the most recent commit

git commit --amend <file1> <file2> ...
  redo previous commit, including changes made to <file1>, <file2>, etc...


Branching
---------

git branch
  list all local branches

git branch -r
  list all remote branches

git branch -a
  list all local and remote branches

git branch <branch>
  create a new branch named <branch>, referencing the same point in history as
  the current branch

git branch <branch> <start-point>
  create a new branch named <branch>, referencing <start-point>, which may be
  specified any way you like, including using a branch name or a tag name

git push <repo> <start-point>:refs/heads/<branch>
  create a new remote branch named <branch>, referencing <start-point> on the
  remote. Repo is the name of the remote.
  Example: git push origin origin:refs/heads/branch-1
  Example: git push origin origin/branch-1:refs/heads/branch-2
  Example: git push origin branch-1 ## shortcut

git branch --track <branch> <remote-branch>
  create a tracking branch. Will push/pull changes to/from another repository.
  Example: git branch --track experimental origin/experimental

git branch --set-upstream <branch> <remote-branch> (As of Git 1.7.0)
  Make an existing branch track a remote branch
  Example: git branch --set-upstream foo origin/foo

git branch -d <branch>
  delete the branch <branch>; if the branch you are deleting points to a
  commit which is not reachable from the current branch, this command
  will fail with a warning.

git branch -r -d <remote-branch>
  delete a remote-tracking branch.
  Example: git branch -r -d wycats/master

git branch -D <branch>
  even if the branch points to a commit not reachable from the current branch,
  you may know that that commit is still reachable from some other branch or
  tag. In that case it is safe to use this command to force git to delete the
  branch.

git checkout <branch>
  make the current branch <branch>, updating the working directory to reflect
  the version referenced by <branch>

git checkout -b <new> <start-point>
  create a new branch <new> referencing <start-point>, and check it out.

git push <repository> :<branch>
  removes a branch from a remote repository.
  Example: git push origin :old_branch_to_be_deleted

git co <branch> <path to new file>
  Checkout a file from another branch and add it to this branch. File
  will still need to be added to the git branch, but it's present.
  Eg. git co remote_at_origin__tick702_antifraud_blocking
  ..../...nt_elements_for_iframe_blocked_page.rb

git show <branch> -- <path to file that does not exist>
  Eg. git show remote_tick702 -- path/to/fubar.txt
  show the contents of a file that was created on another branch and that
  does not exist on the current branch.

git show <rev>:<repo path to file>
  Show the contents of a file at the specific revision. Note: path has to be
  absolute within the repo.

Merging
-------

git merge <branch>
  merge branch <branch> into the current branch; this command is idempotent
  and can be run as many times as needed to keep the current branch
  up-to-date with changes in <branch>

git merge <branch> --no-commit
  merge branch <branch> into the current branch, but do not autocommit the
  result; allows you to make further tweaks

git merge <branch> -s ours
  merge branch <branch> into the current branch, but drops any changes in
  <branch>, using the current tree as the new tree


Cherry-Picking
--------------

git cherry-pick [--edit] [-n] [-m parent-number] [-s] [-x] <commit>
  selectively merge a single commit from another local branch
  Example: git cherry-pick 7300a6130d9447e18a931e898b64eefedea19544


Squashing
---------
WARNING: "git rebase" changes history. Be careful. Google it.

git rebase --interactive HEAD~10
  (then change all but the first "pick" to "squash")
  squash the last 10 commits into one big commit


Conflicts
---------

git mergetool
  work through conflicted files by opening them in your mergetool (opendiff,
  kdiff3, etc.) and choosing left/right chunks. The merged result is staged for
  commit.

For binary files or if mergetool won't do, resolve the conflict(s) manually
and then do:

  git add <file1> [<file2> ...]

Once all conflicts are resolved and staged, commit the pending merge with:

  git commit


Sharing
-------

git fetch <remote>
  update the remote-tracking branches for <remote> (defaults to "origin").
  Does not initiate a merge into the current branch (see "git pull" below).

git pull
  fetch changes from the server, and merge them into the current branch.
  Note: .git/config must have a [branch "some_name"] section for the current
  branch, to know which remote-tracking branch to merge into the current
  branch.  Git 1.5.3 and above adds this automatically.

git push
  update the server with your commits across all branches that are *COMMON*
  between your local copy and the server.  Local branches that were never
  pushed to the server in the first place are not shared.

git push origin <branch>
  update the server with your commits made to <branch> since your last push.
  This is always *required* for new branches that you wish to share. After
  the first explicit push, "git push" by itself is sufficient.

git push origin <branch>:refs/heads/<branch>
  E.g. git push origin twitter-experiment:refs/heads/twitter-experiment
  Which, in fact, is the same as git push origin <branch> but a little
  more obvious what is happening.

Reverting
---------

git revert <rev>
  reverse commit specified by <rev> and commit the result.  This does *not* do
  the same thing as similarly named commands in other VCS's such as "svn
  revert" or "bzr revert", see below

git checkout <file>
  re-checkout <file>, overwriting any local changes

git checkout .
  re-checkout all files, overwriting any local changes.  This is most similar
  to "svn revert" if you're used to Subversion commands


Fix mistakes / Undo
-------------------

git reset --hard
  abandon everything since your last commit; this command can be DANGEROUS.
  If merging has resulted in conflicts and you'd like to just forget about
  the merge, this command will do that.

git reset --hard ORIG_HEAD or git reset --hard origin/master 
  undo your most recent *successful* merge *and* any changes that occurred
  after.  Useful for forgetting about the merge you just did.  If there are
  conflicts (the merge was not successful), use "git reset --hard" (above)
  instead.

git reset --soft HEAD^
  forgot something in your last commit? That's easy to fix. Undo your last
  commit, but keep the changes in the staging area for editing.

git commit --amend
  redo previous commit, including changes you've staged in the meantime.
  Also used to edit commit message of previous commit.


Plumbing
--------

test <sha1-A> = $(git merge-base <sha1-A> <sha1-B>)
  determine if merging sha1-B into sha1-A is achievable as a fast forward;
  non-zero exit status is false.


Stashing
--------

git stash
git stash save <optional-name>
save your local modifications to a new stash (so you can for example "git svn rebase" or "git pull")

git stash apply
  restore the changes recorded in the stash on top of the current working tree
  state

git stash pop
  restore the changes from the most recent stash, and remove it from the stack
  of stashed changes

git stash list
  list all current stashes

git stash show <stash-name> -p
  show the contents of a stash - accepts all diff args

git stash drop [<stash-name>]
  delete the stash

git stash clear
  delete all current stashes


Remotes
-------

git remote add <remote> <remote_URL>
  adds a remote repository to your git config.  Can be then fetched locally.
  Example:
  git remote add coreteam git://github.com/wycats/merb-plugins.git
  git fetch coreteam

git push <remote> :refs/heads/<branch>
  delete a branch in a remote repository

git push <remote> <remote>:refs/heads/<remote_branch>
  create a branch on a remote repository
  Example: git push origin origin:refs/heads/new_feature_name

git push <repository> +<remote>:<new_remote>
  replace a <remote> branch with <new_remote>
  think twice before do this
  Example: git push origin +master:my_branch

git remote prune <remote>
  prune deleted remote-tracking branches from "git branch -r" listing

git remote add -t master -m master origin git://example.com/git.git/
  add a remote and track its master

git remote show <remote>
  show information about the remote server.

git checkout -b <local branch> <remote>/<remote branch>
  Eg git checkout -b myfeature origin/myfeature
  Track a remote branch as a local branch.

git pull <remote> <branch>
git push
For branches that are remotely tracked (via git push) but that complain about non-fast forward commits when doing a git push. The pull synchronizes local and remote, and if all goes well, the result is pushable.

git fetch <remote>
  Retrieves all branches from the remote repository. After
  this 'git branch --track ...' can be used to track a branch
  from the new remote.

Submodules
----------

git submodule add <remote_repository> <path/to/submodule>
  add the given repository at the given path. The addition will be part of the
  next commit.

git submodule update [--init]
  Update the registered submodules (clone missing submodules, and checkout
  the commit specified by the super-repo). --init is needed the first time.

git submodule foreach <command>
  Executes the given command within each checked out submodule.

Removing submodules

   1. Delete the relevant line from the .gitmodules file.
   2. Delete the relevant section from .git/config.
   3. Run git rm --cached path_to_submodule (no trailing slash).
   4. Commit and delete the now untracked submodule files.

Updating submodules
  To update a submodule to a new commit:
    1. update submodule:
        cd <path to submodule>
        git pull
    2. commit the new version of submodule:
        cd <path to toplevel>
        git commit -m "update submodule version"
    3. check that the submodule has the correct version
        git submodule status

  If the update in the submodule is not committed in the
  main repository, it is lost and doing git submodule
  update will revert to the previous version.

Patches
-------

git format-patch HEAD^
  Generate the last commit as a patch that can be applied on another
  clone (or branch) using 'git am'. Format patch can also generate a
  patch for all commits using 'git format-patch HEAD^ HEAD'
  All page files will be enumerated with a prefix, e.g. 0001 is the
  first patch.

git format-patch <Revision>^..<Revision>
  Generate a patch for a single commit. E.g.
  git format-patch d8efce43099^..d8efce43099
  Revision does not need to be fully specified.

git am <patch file>
  Applies the patch file generated by format-patch.

git diff --no-prefix > patchfile
  Generates a patch file that can be applied using patch:
  patch -p0 < patchfile
  Useful for sharing changes without generating a git commit.

Tags
----

git tag -l
  Will list all tags defined in the repository.

git co <tag_name>
  Will checkout the code for a particular tag. After this you'll
  probably want to do: 'git co -b <some branch name>' to define
  a branch. Any changes you now make can be committed to that
  branch and later merged.

Archive
-------

git archive master | tar -x -C /somewhere/else
  Will export expanded tree as tar archive at given path

git archive master | bzip2 > source-tree.tar.bz2
  Will export archive as bz2

git archive --format zip --output /full/path master
  Will export as zip

Git Instaweb
------------

git instaweb --httpd=webrick [--start | --stop | --restart]


Environment Variables
---------------------

GIT_AUTHOR_NAME, GIT_COMMITTER_NAME
  Your full name to be recorded in any newly created commits.  Overrides
  user.name in .git/config

GIT_AUTHOR_EMAIL, GIT_COMMITTER_EMAIL
  Your email address to be recorded in any newly created commits.  Overrides
  user.email in .git/config

GIT_DIR
  Location of the repository to use (for out of working directory repositories)

GIT_WORKING_TREE
  Location of the Working Directory - use with GIT_DIR to specifiy the working
  directory root
  or to work without being in the working directory at all.
