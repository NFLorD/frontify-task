@color
Feature: Listing colors

Scenario: Listing colors
    Given there is a color named "Red", with hex "ff0000"
      And there is a color named "Green", with hex "00ff00"
    When I list colors
    Then I should see 2 items