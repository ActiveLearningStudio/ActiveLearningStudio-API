# Setup ActiveLearningStudio-API development environment on MacOSX or Linux (Fedora, RHEL, CentOS)

## Install Ansible dependencies on Linux

```bash
sudo yum install -y git python3 python3-pip python3-virtualenv python3-libselinux python3-libsemanage python3-policycoreutils
```

## Install Ansible dependencies on MacOSX

```bash
brew install git python gnu-tar
pip3 install virtualenv
```

## Install the latest Python and setup a new Python virtualenv

```bash
# This step might be virtualenv-3 for you. 
virtualenv ~/python

source ~/python/bin/activate
echo "source ~/python/bin/activate" | tee -a ~/.bash_profile
```

## Install the latest Ansible

```bash
pip install setuptools_rust wheel
pip install --upgrade pip
pip install ansible selinux setools
```

## Install dependencies on Linux

```bash
sudo yum install -y maven
```

## Install dependencies on MacOSX

```bash
brew install maven
```

# Setup Ansible

## Install python3 application dependencies

```bash
sudo pip3 install psycopg2-binary
```

## Setup the directory for the project and clone the git repository into it 

```bash
sudo install -d -o $USER -g $USER /usr/local/src/ActiveLearningStudio-API
git clone git@github.com:ActiveLearningStudio-API/ActiveLearningStudio-API.git /usr/local/src/ActiveLearningStudio-API
```

## Setup the environment using the requirements.yml file

```bash
ansible-galaxy install -r /usr/local/src/ActiveLearningStudio-API/ansible/roles/requirements.yml
```

## Install the 4 required roles using the main ansible playbook

```bash
cd /usr/local/src/ActiveLearningStudio-API && ansible-playbook install.yml -K
```

# Configure Eclipse

## Install the Maven plugin for Eclipse

* In Eclipse, go to Help -> Eclipse Marketplace...
* Install "Maven Integration for Eclipse"

## Import the ActiveLearningStudio-API project into Eclipse

* In Eclipse, go to File -> Import...
* Select Maven -> Existing Maven Projects
* Click [ Next > ]
* Browse to the directory: /usr/local/src/ActiveLearningStudio-API
* Click [ Finish ]

## Setup an Eclipse Debug/Run configuration to run and debug ActiveLearningStudio-API

* In Eclipse, go to File -> Debug Configurations...
* Right click on Java Application -> New Configuration
* Name: ActiveLearningStudio-API QuarkusApp
* Project: ActiveLearningStudio-API
* Main class: org.curriki.api.enus.vertx.QuarkusApp

### In the "Arguments" tab

Setup the following VM arguments to disable caching for easier web development: 

```
-DfileResolverCachingEnabled=false -Dvertx.disableFileCaching=true
```

### In the Environment tab

Setup the following variables to setup the Vert.x verticle. 

* CLUSTER_PORT: 10991
* CONFIG_PATH: /usr/local/src/ActiveLearningStudio-API/config/ActiveLearningStudio-API.yml
* SITE_INSTANCES: 5
* VERTXWEB_ENVIRONMENT: dev
* WORKER_POOL_SIZE: 2
* ZOOKEEPER_HOST_NAME: localhost
* ZOOKEEPER_PORT: 2181

Click [ Apply ] and [ Debug ] to debug the application. 
