@color
Feature: Deleting a color

Scenario: Success
    Given there is a color named "Red", with hex "ff0000"
    When I try to delete the color "Red"
    Then I should not have a color named "Red"

Scenario: Failure
    When I try to delete the color "Redd"
    Then I should have an error