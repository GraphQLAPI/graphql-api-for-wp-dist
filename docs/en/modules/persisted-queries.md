# Persisted Queries

Use GraphQL queries to create pre-defined enpoints as in REST, obtaining the benefits from both APIs.

## Description

With **REST**, you create multiple endpoints, each returning a pre-defined set of data.

| Advantages | Disadvantages |
| --- | --- |
| ✅ It's simple | ❌ It's tedious to create all the endpoints |
| ✅ Accessed via `GET` or `POST` | ❌ A project may face bottlenecks waiting for endpoints to be ready |
| ✅ Can be cached on the server or CDN | ❌ Producing documentation is mandatory |
| ✅ It's secure: only intended data is exposed | ❌ It can be slow (mainly for mobile apps), since the application may need several requests to retrieve all the data |

With **GraphQL**, you provide any query to a single endpoint, which returns exactly the requested data.

| Advantages | Disadvantages |
| --- | --- |
| ✅ No under/over fetching of data | ❌ Accessed only via `POST` |
| ✅ It can be fast, since all data is retrieved in a single request | ❌ It can't be cached on the server or CDN, making it slower and more expensive than it could be |
| ✅ It enables rapid iteration of the project | ❌ It may require to reinvent the wheel, such as uploading files or caching |
| ✅ It can be self-documented | ❌ Must deal with additional complexities, such as the N+1 problem |
| ✅ It provides an editor for the query (GraphiQL) that simplifies the task | &nbsp; |

**Persisted queries** combine these 2 approaches together:

- It uses GraphQL to create and resolve queries
- But instead of exposing a single endpoint, it exposes every pre-defined query under its own endpoint

Hence, we obtain multiple endpoints with predefined data, as in REST, but these are created using GraphQL, obtaining the advantages from each and avoiding their disadvantages:

| Advantages | Disadvantages |
| --- | --- |
| ✅ Accessed via `GET` or `POST` | ~~❌ It's tedious to create all the endpoints~~ |
| ✅ Can be cached on the server or CDN | ~~❌ A project may face bottlenecks waiting for endpoints to be ready~~ |
| ✅ It's secure: only intended data is exposed | ~~❌ Producing documentation is mandatory~~ |
| ✅ No under/over fetching of data | ~~❌ It can be slow (mainly for mobile apps), since the application may need several requests to retrieve all the data~~ |
| ✅ It can be fast, since all data is retrieved in a single request | ~~❌ Accessed only via `POST`~~ |
| ✅ It enables rapid iteration of the project | ~~❌ It can't be cached on the server or CDN, making it slower and more expensive than it could be~~ |
| ✅ It can be self-documented | ~~❌ It may require to reinvent the wheel , such asuploading files or caching~~ |
| ✅ It provides an editor for the query (GraphiQL) that simplifies the task | ~~❌ Must deal with additional complexities, such as the N+1 problem~~ 👈🏻 this issue is [resolved by the underlying engine](https://graphql-by-pop.com/docs/architecture/suppressing-n-plus-one-problem.html) |

## How to use

Clicking on the Persisted Queries link in the menu, it displays the list of all the created persisted queries:

<a href="../../images/persisted-queries-page.png" target="_blank">![Persisted Queries in the admin](../../images/persisted-queries-page.png)</a>

A persisted query is a custom post type (CPT). To create a new persisted query, click on button "Add New GraphQL persisted query", which will open the WordPress editor:

<a href="../../images/new-persisted-query.png" target="_blank">![Creating a new Persisted Query](../../images/new-persisted-query.png)</a>

The main input is the GraphiQL client, which comes with the Explorer by default. Clicking on the fields on the left side panel adds them to the query, and clicking on the "Run" button executes the query:

<a href="../../images/graphiql-explorer.gif" target="_blank">![Writing and executing a persisted query](../../images/graphiql-explorer.gif)</a>

When the query is ready, publish it, and its permalink becomes its endpoint:

<a href="../../images/publishing-persisted-query.gif" target="_blank">![Publishing the persisted query](../../images/publishing-persisted-query.gif)</a>

Appending `?view=source` to the permalink, it will show the persisted query and its configuration (as long as the user has access to it):

<a href="../../images/persisted-query-source.png" target="_blank">![Persisted query source](../../images/persisted-query-source.png)</a>

By default, the persisted query's endpoint has path `/graphql-query/`, and this value is configurable through the Settings:

<a href="../../images/settings-persisted-queries.png" target="_blank">![Persisted query Settings](../../images/settings-persisted-queries.png)</a>

## Editor Inputs

These are the inputs in the body of the editor:

<table markdown="1">
<thead>
<tr>
    <th>Input</th>
    <th>Description</th>
</tr>
</thead>
<tbody>
<tr>
  <td>**Title**</td>
  <td>Persisted query's title</td>
</tr>
<tr>
  <td>**GraphiQL client**</td>
  <td>Editor to write and execute the GraphQL query: <ul markdown="1"><li>Write the query on the textarea</li><li>Declare variables inside the query, and declare their values on the variables input at the bottom</li><li>Click on the "Run" button to execute the query</li><li>Obtain the results on the input on the right side</li><li>Click on "Docs" to inspect the schema information</li></ul>The Explorer (shown only if module `GraphiQL Explorer` is enabled) allows to click on the fields, and these are automatically added to the query</td>
</tr>
<tr>
  <td>**Schema configuration**</td>
  <td>From the dropdown, select the schema configuration that applies to the persisted query, or one of these options: <ul markdown="1"><li>`"Default"`: the schema configuration is the one selected on the plugin's Settings</li><li>`"None"`: the persisted query will be unconstrained</li><li>`"Inherit from parent"`: Use the same schema configuration as the parent persisted query.<br/>This option is available when module `API Hierarchy` is enabled, and the persisted query has a parent query (selected on the Document settings)</li></ul></td>
</tr>
<tr>
  <td>**Options**</td>
  <td>Customize the behavior of the persisted query: <ul markdown="1"><li>**Enabled?:** If the persisted query is enabled.<br/>It's useful to disable a persisted query it's a parent query in an API hierarchy</li><li>**Accept variables as URL params?:** Allow URL params to override the values for variables defined in the GraphiQL client</li><li>**Inherit query from ancestor(s)?:** Use the same query as the parent persisted query.<br/>This option is available when module `API Hierarchy` is enabled, and the persisted query has a parent query (selected on the Document settings)</li></ul></td>
</tr>
</tbody>
</table>

These are the inputs in the Document settings:

| Input | Description | 
| --- | --- |
| **Permalink** | The endpoint under which the persisted query will be available |
| **Categories** | Can categorize the persisted query.<br/>Eg: `mobile`, `app`, etc |
| **Excerpt** | Provide a description for the persisted query.<br/>This input is available when module `Excerpt as Description` is enabled |
| **Page attributes** | Select a parent persisted query.<br/>This input is available when module `API Hierarchy` is enabled |

<!-- ## Settings

| Option | Description | 
| --- | --- |
| **Base path** | The base path for the custom endpoint URL. It defaults to `graphql-query` | -->

## Resources

Video showing how to create a persisted query: <https://vimeo.com/443790273>.
