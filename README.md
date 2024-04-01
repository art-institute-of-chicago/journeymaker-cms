![Art Institute of Chicago](https://raw.githubusercontent.com/Art-Institute-of-Chicago/template/master/aic-logo.gif)

# Name of Project
> Additional Sub-Title If Necessary

Summary of the project. This is the first thing you read when you view this
project. This is a great place to summarize the goals or intentions of this
project. Generally speaking, this section is optional, but is a nice way to
get a snapshot of what this project is about.

Also include information on the maturity of the project, like when it was
launched, what its current production environment is like, and who it is
maintained by.

## Features

What are all the bells and whistles that are significant or unique to this project?

* What's the main functionality
* What new thing does this project provide?
* What unique feature does this project include?

## Overview

Describe the architecture in which this project fits, and point to any other repos
that make up the full stack of software. Describe how each piece fits
together.

## Requirements

List any and all requirements, included hardware, server software, and third-party
libraries.

## Installing

A quick introduction of the minimal setup you need to get a hello world up and
running.

```shell
# Comment your code
packagemanager install aic-project

# Descibe in brief what each step does
aic-project start

# Or why this step is required
aic-project some-setup-function-if-necessary
```

Here you should say more thoroughly what actually happens when you execute the code above.

## Developing

Here is a brief intro about what a developer must do in order to start developing
the project further:

```shell
git clone https://github.com/your/aic-project.git
cd aic-project/
packagemanager install
```

And state what happens step-by-step.

If a developer needs to copy a sample configuration file to get their local instance
going, provide the most minimum effort needed here. More details on configuration can
be included in a later section on [Configuration](#configuration).

### Building

If your project needs some additional steps for the developer to build the
project after some code changes, state them here:

```shell
./configure
make
make install
```

Here again you should state what actually happens when the code above gets
executed.

### Deploying / Publishing

In case there's some step you have to take that publishes this project to a
server, here is where to state it.

```shell
packagemanager deploy aic-project -s server.com -u username -p password
```

And again you'd need to tell what the previous code actually does.

## Configuration

Here you should write what are all of the configurations a user can enter when
using the project, and which file each config is set if applicable.

### Configuration file path

#### Configuration 1 Name
Type: `String`
Default: `'default value'`

State what it does and how you can use it. If needed, you can provide
an example below.

Example:
```bash
aic-project "Some other value"  # Prints "Hello World"
```

#### Configuration 2 Name
Type: `Number|Boolean`
Default: 100

Copy-paste as many of these as you need.

## Contributing

We encourage your contributions. Please fork this repository and make your changes in a separate branch. To better understand how we organize our code, please review our [version control guidelines](https://docs.google.com/document/d/1B-27HBUc6LDYHwvxp3ILUcPTo67VFIGwo5Hiq4J9Jjw).

```bash
# Clone the repo to your computer
git clone git@github.com:your-github-account/aic-project.git

# Enter the folder that was created by the clone
cd aic-project

# Run the install script
./install.sh

# Start a feature branch
git checkout -b feature/good-short-description

# ... make some changes, commit your code

# Push your branch to GitHub
git push origin feature/good-short-description
```

Then on github.com, create a Pull Request to merge your changes into our
`develop` branch.

This project is released with a Contributor Code of Conduct. By participating in
this project you agree to abide by its [terms](CODE_OF_CONDUCT.md).

We welcome bug reports and questions under GitHub's [Issues](issues). For other concerns, you can reach our engineering team at [engineering@artic.edu](mailto:engineering@artic.edu)

If there's anything else a developer needs to know (e.g. the code style
guide), you should link it here. If there's a lot of things to take into
consideration, separate this section to its own file called `CONTRIBUTING.md`
and say that it exists here.

## Acknowledgements

Name who designed and developed this project. Reference someone's code you used,
list contributors, insert an external link or thank people. If there's a lot to
inclue here, separate this section to its own file called `CONTRIBUTORS.md` and
say that it exists here.

## Licensing

This project is licensed under the [GNU Affero General Public License
Version 3](LICENSE).
