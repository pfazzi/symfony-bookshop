Feature: Book catalog
  In order to populate the catalog
  As an admin
  I need to be able to add products to the catalog

  Scenario: Adding a new book
    Given there is an author named "Robert Cecil Martin"
    When I add "Content-Type" header equal to "application/json"
    And I add "Accept" header equal to "application/json"
    And I send a "POST" request to "/api/books/" with body:
    """
    {
	  "isbn": "9781234567897",
      "title": "Clean Code",
      "author_id": "e7348bc0-b021-47f5-b61c-cd27a13f9519",
      "price": 19.99
    }
    """
    Then the response status code should be 201
    And the response should be in JSON
    And the header "Content-Type" should be equal to "application/json"
    And the JSON nodes should contain:
      | title                   | Clean Code              |
      | isbn                    | 9781234567897           |