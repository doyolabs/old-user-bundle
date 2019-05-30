Feature: Group
  In order to easily manage user permissions
  As administrator
  I should able to manage group

  Background:
    Given I have logged in as admin

  Scenario: Request api for existing group
    Given I request api for group 'dummy'
    Then the response status code should be 200
    And the JSON node id should exist
    And the JSON should be a superset of:
    """
    {
      "name": "dummy",
      "roles": ["ROLE_USER"]
    }
    """

  Scenario: Successfully create new group
    Given I don't have group new_group
    When I request api to create group with:
    """
    {
      "name": "new_group",
      "roles": ["ROLE_NEW"]
    }
    """
    Then the response status code should be 201
    And the JSON should be a superset of:
    """
    {
      "name": "new_group",
      "roles": ["ROLE_NEW"]
    }
    """

  Scenario: Successfully update group
    Given there is group update
    When I request api to update group update with:
    """
    {
      "roles": ["ROLE_EDITED"]
    }
    """
    Then the response status code should be 200
    And the JSON should be a superset of:
    """
    {
      "roles": ["ROLE_EDITED"]
    }
    """

  Scenario: Successfully delete group
    Given there is group delete
    When I request api to delete group delete
    Then the response status code should be 204

  Scenario: Add user to group
    Given there is group test with role ROLE_GROUP
    And there is user with username test
    When I request api to add user test to group test
    Then the response status code should be 200
    And the JSON should be a superset of:
    """
    {
      "roles": ["ROLE_USER","ROLE_GROUP"]
    }
    """
