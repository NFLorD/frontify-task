@color
Feature: Creating a color

Scenario: Success
    When I try to create a color named "Red", with hex "ff0000"
    Then I should have a color named "Red"

Scenario: Wrong name
    When I try to create a color named " ", with hex "ff0000"
    Then I should have an error with message "name: This value should not be blank."

Scenario: Wrong hex
    When I try to create a color named "Red", with hex "f872"
    Then I should have an error with message "hex: This value should have exactly 6 characters."

Scenario: Duplicate hex
    When I try to create a color named "Red", with hex "ff0000"
    When I try to create a color named "Green", with hex "ff0000"
    Then I should have an error with message 'hex: A color with hex "ff0000" already exists.'