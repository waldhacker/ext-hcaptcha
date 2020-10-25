#!/bin/bash

# needs https://pypi.org/project/gitchangelog/
GITCHANGELOG_CONFIG_FILENAME=.gitchangelog.rc
gitchangelog > Documentation/Changelog.rst

GITCHANGELOG_CONFIG_FILENAME=.gitchangelog-md.rc
gitchangelog > CHANGELOG.md
