@color
Feature: Creating a color

Scenario: Success
    When I try to create a color named "Red", with hex "ff0000"
    Then I should have no error

Scenario: Wrong name
    When I try to create a color named " ", with hex "ff0000"
    Then I should have an error

Scenario: Wrong hex
    When I try to create a color named "Red", with hex "f872"
    Then I should have an error

Scenario: Duplicate hex
    When I try to create a color named "Red", with hex "ff0000"
    When I try to create a color named "Green", with hex "ff0000"
    Then I should have an error