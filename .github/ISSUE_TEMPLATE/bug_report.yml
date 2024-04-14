name: Bug report
description: Report a bug
title: '[Bug]: '
labels:
  - bug
body:
  - type: input
    attributes:
      label: Describe the bug
      description: A clear and concise description of what the bug is.
    validations:
      required: true
  - type: textarea
    attributes:
      label: Configuration
      description: >-
        Provide a complete `docker-compose.yaml` and\or `config.php`
        configuration file
  - type: input
    attributes:
      label: Version
      description: What version(s) of Intruder Alert can you reproduce this bug on?
    validations:
      required: true
  - type: dropdown
    attributes:
      label: ' Deployment Method'
      description: How are you deployed Intruder Alert?
      options:
        - docker-compose
        - Bare-metal
        - Other
    validations:
      required: true
  - type: dropdown
    attributes:
      label: PHP Version
      description: What version of php are you using to run Intruder Alert?
      options:
        - '8.3'
        - '8.2'
        - '8.1'
        - Other
    validations:
      required: true
  - type: textarea
    attributes:
      label: Additional context
      description: Add any other context about the problem here.
  - type: markdown
    attributes:
      value: >-
        This template was generated with [Issue Forms
        Creator](https://issue-forms-creator.netlify.app)
