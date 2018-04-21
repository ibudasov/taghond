[![Test Coverage](https://api.codeclimate.com/v1/badges/b6f06be3f641b0da68b1/test_coverage)](https://codeclimate.com/github/ibudasov/taghond/test_coverage)
[![Maintainability](https://api.codeclimate.com/v1/badges/b6f06be3f641b0da68b1/maintainability)](https://codeclimate.com/github/ibudasov/taghond/maintainability)

# taghond
Automated tool for setting up tags to pictures before uploading them to image stocks. 

# why?
Every image stock requires putting tags to pictures. 
Most of them pick up tags from picture's metadata. 
So, if we could set up tags for pictures in some sort automated mode - it would be great. 

# how?
But this is just not simple: you need to set up basic tags, which are the same for every picture in the bunch, and the rest of tags suppose to be 'guessed'.
For 'guessing' of tags we're going to use image recognition service of Google of Amazon, not sure so far. 

# tech details?
I can see it as a console command, which gonna run Docker container with php application inside.
Command will take a directory with pictures as a first param.
Second param will be basic tags. 
There could be the third parameter - geotag with location, because of image recognition service will work better with it. 


Also I can think of EventSourcing, it will provide nice report of updated images at the end.
