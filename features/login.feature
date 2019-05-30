Feature: Login feature
  In order to start using application
  As a user
  I should be able to login into application

  Background:
    Given there is user with username dummy_1 and password "s3cr3t"

  Scenario: Login with correct username and password
    When I send a JSON POST request to "/login-check" with body:
    """
    {
      "username": "dummy_1",
      "password": "s3cr3t"
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node token should exist

  Scenario: Login with invalid username
    When I send a JSON POST request to "/login-check" with body:
    """
    {
      "username": "foo",
      "password": "bar"
    }
    """
    Then the response status code should be 401
    And the response should be in JSON
    And the JSON should be a superset of:
    """
    {
      "code": 401,
      "message": "Bad credentials."
    }
    """

  Scenario: Access secure resource
    Given I have logged in with username dummy_1
    And I send request api for user dummy_1
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node id should exist
    And the JSON node lastLogin should exist
    And the JSON should be a superset of:
    """
    {
      "username": "dummy_1",
      "fullName": "Test User",
      "email": "dummy_1@example.org",
      "enabled": true,
      "roles": ["ROLE_USER"]
    }
    """

  Scenario: Access secure resource without login
    When I send request api for user dummy_1
    Then the response status code should be 401
    And the response should be in JSON
    And the JSON should be a superset of:
    """
    {
      "code": 401,
      "message": "JWT Token not found"
    }
    """
