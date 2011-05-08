Feature: Argument Transformation
  As a user of cuke4php
  I want to use step argument transforms
  So that I can simplify my code
  Example transforms can be found in WireSteps.php
  
Scenario: An argument is transformed to an integer
  Given I store "123" into "Foo"
  Then "Foo" should be a kind of "integer"
  
Scenario: use a transform to substitute variable in arguments
  Given I store "foo" into "Bar"
  And I store "{Bar}" into "Foo"
  Then "Foo" should equal "foo"
  
Scenario: An argument is transformed, but the second defined transform wins
  Given I store "abcd" into "Foo"
  Then "Foo" should equal "ABCD"

Scenario: transform a table
  Given "table" is:
    | reverse |
    | one |
    | two |
    | three |
  Then "table" should equal:
    | reverse |
    | three |
    | two |
    | one |
    
Scenario: transform a table with two columns
  Given "table" is:
    | KEY | VALUE |
    | one | a |
    | two | b |
    | three | c |
  Then "table" should equal:
    | KEY | VALUE |
    | ONE | A |
    | TWO | B |
    | THREE | C |
