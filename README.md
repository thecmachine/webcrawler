## Webcrawler Instructions

- [Clone Github Repository](https://github.com/thecmachine/webcrawler)
- ```git clone https://github.com/thecmachine/webcrawler.git```.
- Checkout MASTER branch (Not Main).
- This will run in MacOS BASH Terminal, no docker container necessary.

## Run Webcrawler Artisan Command

- In Terminal, run ```php artisan webcrawler:crawl {url}``` or ```php artisan webcrawler:crawl https://wiprodigital.com```.
- You will see MAIN Page Internal, External and Image Urls as well as SubPage Internal, External and Image Urls.

- To run unit tests use ```php artisan test```.

## My Strategy for assignment
- No views were necessary to see the results of the Webcrawler.
- IF a view/table was required, I would have considered using a Domain and Url Models/Database Tables for persistence.
- A user could enter a URL into a textbox and run the webcrawler job here via a Controller and save the results into the database.
- A user could schedule the job to run automatically every hour/evening/week and keep only the most recent URLs found in the Database for latest and greatest markup.
- It's a simple webcrawler with nearly infinite scalability and utility

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
