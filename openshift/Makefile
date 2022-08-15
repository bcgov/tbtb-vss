#tbtb system monitor Makefile

## get values from Github actions yml file. under env
export GIT_LOCAL_BRANCH?=$(shell git rev-parse --abbrev-ref HEAD)
export NAMESPACE := $(or $(NAMESPACE), 06cb9b-dev)
export APP_NAME := $(or $(PROJECT_NAME), tbtb-vss)
export BUILD_ID := $(or $(BUILD_ID), 1)
export BUILD_REF := $(or $(BUILD_REF), $(GIT_LOCAL_BRANCH))
export BUILD_NAMESPACE := $(or $(BUILD_NAMESPACE), 06cb9b-tools)
export DOMAIN := $(or $(DOMAIN), apps.silver.devops.gov.bc.ca)
export HOST_PREFIX := $(or $(HOST_PREFIX), tbtb-vss)
export SOURCE_REPOSITORY_BRANCH := $(or $(SOURCE_REPOSITORY_BRANCH), dev)

#if
ifeq ($(SOURCE_REPOSITORY_BRANCH),dev)
define OC_USER_ID
"1009370000"
endef
else
define OC_USER_ID
"1009380000"
endef
endif


#define general variables
define LABEL_NAME
"TBTB-VSS"
endef

define BUILD_TAG
"latest"
endef

define HOST
"$(HOST_PREFIX)-$(BUILD_NAMESPACE).apps.silver.devops.gov.bc.ca.$(DOMAIN)"
endef

define SOURCE_REPOSITORY_REF
"https://github.com/bcgov/tbtb-vss"
endef


define rollout_and_wait
@oc -n $(NAMESPACE) rollout status $1
endef

oc-build-web: | print-status build-web
oc-deploy-web: | print-status deploy-web
oc-build-patroni: | print-status build-patroni
oc-deploy-patroni: | print-status deploy-patroni

print-status:
	@echo " +---------------------------------------------------------+ "
	@echo " | Current Settings										| "
	@echo " +---------------------------------------------------------+ "
	@echo " | PROJECT:	  			$(APP_NAME)"
	@echo " | BRANCH:	   			$(BUILD_REF)"
	@echo " | NAMESPACE:				$(NAMESPACE)"
	@echo " | BUILD_NAMESPACE:				$(BUILD_NAMESPACE)"
	@echo " | GIT_LOCAL_BRANCH:				$(GIT_LOCAL_BRANCH)"
	@echo " +---------------------------------------------------------+ "
	@echo " | BUILD_ID: 	$(BUILD_ID) "
	@echo " | BUILD_TAG: 	$(BUILD_TAG) "
	@echo " | HOST: 	$(HOST) "
	@echo " | NAME: 	$(HOST_PREFIX) "
	@echo " | REPO_NAME: 	$(SOURCE_REPOSITORY_REF) "
	@echo " | REPO_BRANCH: 	$(SOURCE_REPOSITORY_BRANCH) "
	@echo " | OC_USER_ID: 	$(OC_USER_ID) "

#	"$(HOST_PREFIX)-$(BUILD_NAMESPACE).apps.silver.devops.gov.bc.ca.$(DOMAIN)"
	@echo " +---------------------------------------------------------+ "

build-web:
	test -n "$(BUILD_REF)"
	test -n "$(BUILD_NAMESPACE)"
# 	@echo "+\n++ SET secret Web\n+"
# 	@oc -n $(BUILD_NAMESPACE) process -f $(BUILD_TEMPLATE_PATH) -p TAG=$(BUILD_TAG) -p SOURCE_REPOSITORY_REF=$(BUILD_REF) -p NAME=$(Web) | oc -n $(BUILD_NAMESPACE) apply -f -
# 	@oc -n $(BUILD_NAMESPACE) start-build bc/$(Web) --wait
# 	@oc apply -f bc.yaml
# 	@oc -n $(BUILD_NAMESPACE) set build-secret --pull bc/$(Web) $(GIT_SECRET_KEY)
	@echo "+\n++ PROCESSING Web\n+"
	@oc -n $(BUILD_NAMESPACE) process -f bc.yaml -p OC_USER_ID=$(OC_USER_ID) -p NAME=$(HOST_PREFIX) -p BUILD_NAMESPACE=$(BUILD_NAMESPACE) -p IMAGE_TAG=$(BUILD_TAG) -p REPO_NAME=$(SOURCE_REPOSITORY_REF) -p REPO_BRANCH=$(SOURCE_REPOSITORY_BRANCH) | oc -n $(BUILD_NAMESPACE) apply -f -
	@echo "+\n++ BUILDING Web\n+"
	@oc -n $(BUILD_NAMESPACE) start-build bc/$(HOST_PREFIX) --wait
	@echo "+\n++ FINISHED BUILDING Web\n+"

deploy-web:
	@echo "+\n++ START DEPLOYING Web\n+"
	test -n "$(NAMESPACE)"
	test -n "$(BUILD_NAMESPACE)"
	@echo "+\n++ Deploying Web with\n+"
	@oc -n $(NAMESPACE) process -f dc_latest.yaml -p LABEL_NAME=$(LABEL_NAME) -p NAME=$(HOST_PREFIX) -p IMAGE_TAG=$(BUILD_TAG) -p BUILD_NAMESPACE=$(BUILD_NAMESPACE) -p NAMESPACE=$(NAMESPACE) -p REPO_BRANCH=$(SOURCE_REPOSITORY_BRANCH) | oc -n $(NAMESPACE) apply -f -
	$(call rollout_and_wait,dc/$(HOST_PREFIX))
	@echo "+\n++ FINISHED Deploying Web\n+"

build-patroni:
	@oc -n $(BUILD_NAMESPACE) process -f patroni-build.yaml  -p NAME=tbtb-vss-patroni -p TAG="12-latest" -p BASE_IMAGE_NAME=postgres -p BASE_IMAGE_TAG="12" -p SOURCE_REPOSITORY_URL="https://github.com/BCDevOps/platform-services.git" -p SOURCE_REPOSITORY_REF="master" -p SOURCE_CONTEXT_DIR="apps/pgsql/patroni/docker" | oc -n $(BUILD_NAMESPACE) apply -f -
	@oc -n $(BUILD_NAMESPACE) start-build bc/tbtb-vss-patroni --wait

deploy-patroni:
	@oc -n $(NAMESPACE) process -f patroni-deploy.yaml -p LABEL_NAME=$(LABEL_NAME) -p BUILD_NAMESPACE=$(BUILD_NAMESPACE) -p IMAGE_STREAM_TAG=patroni:12-latest | oc -n $(NAMESPACE) apply -f -
