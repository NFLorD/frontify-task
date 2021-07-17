@color
Feature: Deleting a color
    Given there is a color named "Red", with hex "ff0000"

Scenario: Success
    When I try to delete the color "Red"
    Then I should have a color named "Red"

Scenario: Failure
    When I try to delete the color "Redd"