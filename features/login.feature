Feature: Login feature
  In order to start using application
  As a user
  I should be able to login into application

  Scenario: Login with correct username and password
    Given there is user with username dummy_1 and password "test"
    When I send a JSON POST request to "/login-check" with body:
    """
    {
      "username": "dummy_1",
      "password": "test"
    }
    """
    Then the response status code should be 200
    And the response should be in JSON
    And the JSON node token should exist

  Scenario: Login with invalid username
    Given there is user with username dummy_1 and password "test"
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
      "message": "Bad credentials"
    }
    """

  Scenario: Access secure resource
    Given I have logged in with username dummmy_1
    And I send a JSON GET request to '/api/users'
    Then the response status code should be 200
    And the response should be in JSON
    #And the response should contain "foo"
