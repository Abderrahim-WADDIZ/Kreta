# This file belongs to Kreta.
# The source code of application includes a LICENSE file
# with all information about license.
#
# @author benatespina <benatespina@gmail.com>
# @author gorkalaucirica <gorka.lauzirika@gmail.com>

@api-o
Feature: Manage status transition
  In order to manage status transitions
  As an API status transition
  I want to be able to GET, POST, PUT and DELETE statuses

  Background:
    Given the following users exist:
      | id | firstName | lastName | email           | password | createdAt  |
      | 0  | Kreta     | User     | user@kreta.com  | 123456   | 2014-10-20 |
      | 1  | Kreta     | User2    | user2@kreta.com | 123456   | 2014-10-20 |
      | 2  | Kreta     | User3    | user3@kreta.com | 123456   | 2014-10-20 |
    And the following workflows exist:
      | id | name       | creator        |
      | 0  | Workflow 1 | user@kreta.com |
      | 1  | Workflow 2 | user@kreta.com |
    And the following projects exist:
      | id | name           | shortName | creator        | workflow   |
      | 0  | Test project 1 | TPR1      | user@kreta.com | Workflow 1 |
      | 1  | Test project 2 | TPR2      | user@kreta.com | Workflow 2 |
    And the following statuses exist:
      | id | color   | name        | workflow   |
      | 0  | #27ae60 | Open        | Workflow 1 |
      | 1  | #2c3e50 | In progress | Workflow 1 |
      | 2  | #f1c40f | Resolved    | Workflow 1 |
    And the following status transitions exist:
      | id | name            | status      | initialStates    |
      | 0  | Start progress  | Open        | In progress      |
      | 1  | Reopen progress | In progress | Open,Resolved    |
      | 2  | Finish progress | Resolved    | Open,In progress |
    And the following participants exist:
      | project        | user            | role             |
      | Test project 1 | user3@kreta.com | ROLE_PARTICIPANT |
      | Test project 1 | user2@kreta.com | ROLE_PARTICIPANT |
      | Test project 2 | user2@kreta.com | ROLE_PARTICIPANT |
    And the following issues exist:
      | id | numericId | project        | title        | description | reporter       | assignee       | type    | status   | priority | createdAt  |
      | 0  | 1         | Test project 1 | Test issue 1 | Description | user@kreta.com | user@kreta.com | initial | Open     | 1        | 2014-10-21 |
      | 1  | 2         | Test project 1 | Test issue 2 | Description | user@kreta.com | user@kreta.com | initial | Resolved | 1        | 2014-10-21 |
    And the following tokens exist:
      | token          | expiresAt | scope | user            |
      | access-token-0 | null      | user  | user@kreta.com  |
      | access-token-1 | null      | user  | user2@kreta.com |
      | access-token-2 | null      | user  | user3@kreta.com |

  Scenario: Getting all the transitions of workflow 0
    Given I am authenticating with "access-token-0" token
    When I send a GET request to "/app_test.php/api/workflows/0/transitions"
    Then the response code should be 200
    And the response should contain json:
    """
      [{
        "initial_states": [{
          "type": "normal",
          "name": "In progress",
          "id": "1",
          "color": "#2c3e50"
        }],
        "name": "Start progress",
        "id": "0",
        "_links": {
          "self": {
            "href": "http://localhost/app_test.php/api/workflows/0/transitions/0"
          },
          "transitions": {
            "href": "http://localhost/app_test.php/api/workflows/0/transitions"
          },
          "workflow": {
            "href": "http://localhost/app_test.php/api/workflows/0"
          }
        }
      }, {
        "initial_states": [{
          "type": "normal",
          "name": "Open",
          "id": "0",
          "color": "#27ae60"
        }, {
          "type": "normal",
          "name": "Resolved",
          "id": "2",
          "color": "#f1c40f"
        }],
        "name": "Reopen progress",
        "id": "1",
        "_links": {
          "self": {
            "href": "http://localhost/app_test.php/api/workflows/0/transitions/1"
          },
          "transitions": {
            "href": "http://localhost/app_test.php/api/workflows/0/transitions"
          },
          "workflow": {
            "href": "http://localhost/app_test.php/api/workflows/0"
          }
        }
      }, {
        "initial_states": [{
          "type": "normal",
          "name": "Open",
          "id": "0",
          "color": "#27ae60"
        }, {
          "type": "normal",
          "name": "In progress",
          "id": "1",
          "color": "#2c3e50"
        }],
        "name": "Finish progress",
        "id": "2",
        "_links": {
          "self": {
            "href": "http://localhost/app_test.php/api/workflows/0/transitions/2"
          },
          "transitions": {
            "href": "http://localhost/app_test.php/api/workflows/0/transitions"
          },
          "workflow": {
            "href": "http://localhost/app_test.php/api/workflows/0"
          }
        }
      }]
    """

  Scenario: Getting the 0 transition
    Given I am authenticating with "access-token-0" token
    When I send a GET request to "/app_test.php/api/workflows/0/transitions/0"
    Then the response code should be 200
    And the response should contain json:
    """
      {
        "initial_states": [{
          "type": "normal",
          "name": "In progress",
          "id": "1",
          "color": "#2c3e50"
        }],
        "name": "Start progress",
        "id": "0",
        "_links": {
          "self": {
            "href": "http://localhost/app_test.php/api/workflows/0/transitions/0"
          },
          "transitions": {
            "href": "http://localhost/app_test.php/api/workflows/0/transitions"
          },
          "workflow": {
            "href": "http://localhost/app_test.php/api/workflows/0"
          }
        }
      }
    """

  Scenario: Getting the 0 status with user which is not a participant
    Given I am authenticating with "access-token-2" token
    When I send a GET request to "/app_test.php/api/workflows/1/transitions/0"
    Then the response code should be 403
    And the response should contain json:
    """
      {
        "error": "Not allowed to access this resource"
      }
    """

  Scenario: Getting the unknown transition
    Given I am authenticating with "access-token-0" token
    When I send a GET request to "/app_test.php/api/workflows/0/transitions/unknown-transition"
    Then the response code should be 404
    And the response should contain json:
    """
      {
        "error": "Does not exist any entity with unknown-transition id"
      }
    """
