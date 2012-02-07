This is home to the Application Programming Interface (API) for Drivers of Change (DoC)
<br /><br />
The DoC API can be accessed at <a href="<?php echo $base_url."api"?>"><?php echo $base_url."api"?></a>
<br /><br />
This interface allows you to get data from the DoC database (DB). Below you will find examples of how you can use the API to get to data you may be interested in. By default, the data requested is returned as text (a String) in the JSON format. To make it easier to read, the exmaples below request to receive data in XML format (using the format=xml parameter).
<br /><br />
The DB contains information on resources such as <a href="<?php echo $base_url."api/card/?format=xml"?>">Card</a>s in <a href="<?php echo $base_url."api/deck/?id=1&format=xml"?>">Deck</a>s, <a href="<?php echo $base_url."api/event/?id=3&format=xml"?>">Event</a>s they were used in and <a href="<?php echo $base_url."api/vote/?event_id=3&format=xml"?>">Vote</a>s on them.
<br /><br />
<a href="<?php echo $base_url."api/card/?format=xml"?>"><?php echo $base_url."api/card/"?></a> provides access to the Card resource
<br />
<a href="<?php echo $base_url."api/card/?id=1&format=xml"?>"><?php echo $base_url."api/card/?id=1"?></a> gives you all the info for card id 1
<br /><br />
For more complex operations, more than one step may be required. Getting all Cards with a specific Tag, for example:
<br />
<a href="<?php echo $base_url."api/tag/?format=xml"?>"><?php echo $base_url."api/tag/"?></a> gets you all tags and their ids
<br />
<a href="<?php echo $base_url."api/card/?tag_id=1&format=xml"?>"><?php echo $base_url."api/card/?tag_id=1"?></a> gets you all cards tagged with a specific tag id 
<br />

<br /><br />
Adding /usage to any resource endpoint, will give detailed, technical info on how it should be used, for example:
<br />
<a href="<?php echo $base_url."api/deck/usage?format=xml"?>"><?php echo $base_url."api/deck/usage"?></a>
<br /><br />
