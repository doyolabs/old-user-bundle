Feature: Admin CRUD
  In order to manage application users
  As Administrator
  I should able to manage user

  Background:
    Given I have logged in as admin

  Scenario: Successfully create user
    Given I don't have user with username new_user
    When I send api request to create user with:
    """
    {
      "username": "new_user",
      "email": "new_user@example.org",
      "plainPassword": "s3cr3t",
      "fullName": "New User"
    }
    """
    Then the response status code should be 201
    And the response should be in JSON
    And the JSON node id should exist
    And the JSON should be a superset of:
    """
    {
      "username": "new_user",
      "email": "new_user@example.org",
      "fullName": "New User"
    }
    """
    
  Scenario: Get api for spesific user
    Given there is user with username dummy_1
    When I request api for user dummy_1
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node id should exist
    And the JSON node lastLogin should exist
    And the JSON node plainPassword should not exist
    And the JSON node password should not exist
    And the JSON should be a superset of:
    """
    {
      "username": "dummy_1",
      "email": "dummy_1@example.org",
      "fullName": "Test User",
      "roles": ["ROLE_USER"]
    }
    """

  Scenario: Successfully update user
    Given there is user with username dummy_1
    When I send api to update user dummy_1 with:
    """
    {
      "fullName": "Dummy User Edited"
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON should be a superset of:
    """
    {
      "fullName": "Dummy User Edited"
    }
    """

  Scenario: Successfully delete user
    Given there is user with username dummy_1
    When I send api to delete user dummy_1
    Then the response status code should be 204

  Scenario: Update user with invalid data
    Given there is user with username dummy_1
    When I send api to update user dummy_1 with:
    """
    {
      "username": "",
      "email": ""
    }
    """
    Then the response status code should be 400
    And the response should be in JSON
    And the JSON should be a superset of:
    """
    {
      "violations": [
        {
          "propertyPath": "username",
          "message": "trans('doyo_user.username.blank','validators')"
        },
        {
          "propertyPath": "email",
          "message": "trans('doyo_user.email.blank','validators')"
        }
      ]
    }
    """

  Scenario: Create user with existing username
    Given there are 1 dummy users
    When I send api request to create user with:
    """
    {
      "username": "dummy_1",
      "email": "dummy_1@example.org",
      "plainPassword": "bar"
    }
    """
    Then the response status code should be 400
    And the JSON should be a superset of:
    """
    {
      "violations": [
        {
          "propertyPath": "username",
          "message": "trans('doyo_user.username.already_used', 'validators')"
        },
        {
          "propertyPath": "email",
          "message": "trans('doyo_user.email.already_used', 'validators')"
        }
      ]
    }
    """