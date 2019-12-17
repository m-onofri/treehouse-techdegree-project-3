# treehouse-techdegree-project-3

This is the third project of PHP Treehouse Techdegree, and the goal is yo build a personal learning journal using PHP Data Objects (PDO) and a SQLite database. 
The personal learning journal, lets a user add and edit journal entries and store the results in a database. The result is a useful, blog-like web application. 


## How to install 

Clone the git repository in the folder of your choice:
```
git clone https://github.com/m-onofri/treehouse-techdegree-project-3.git
```

Run the server:
```
cd public
php -S localhost:4000
```

In your browser, go to http://localhost:4000/, and enjoy the app!


## Database structure
I used a SQLite database called **journal.db** that contains three tables:

 * **entries** (id, title, date, time_spent, learned, resources)
 * **tags** (id, name)
 * **entries_tags** (entries_id, tags_id)


## Main features

 * PDO connection to the SQLite.
 * User can add, update and delete journal entries.
 * The app displays the list of journal entries, each one with a link to display the journal entry with all fields (title, date, time_spent, learned, tags and resources).
 * User can filter the journal entries using tags.


## Cross-browser consistency

The project was checked on MacOS in Chrome, Firefox, Opera and Safari, and on these browsers it works properly.





