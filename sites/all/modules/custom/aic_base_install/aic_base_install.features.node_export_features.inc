<?php
/**
 * @file
 * aic_base_install.features.node_export_features.inc
 */

/**
 * Implements hook_node_export_features_default().
 */
function aic_base_install_node_export_features_default() {
  $node_export = array(
  'code_string' => 'array(
  (object) array(
      \'changed\' => NULL,
      \'cid\' => \'0\',
      \'comment\' => \'1\',
      \'comment_count\' => \'0\',
      \'created\' => NULL,
      \'data\' => NULL,
      \'field_activity_image\' => array(),
      \'files\' => array(),
      \'language\' => \'und\',
      \'last_comment_name\' => NULL,
      \'last_comment_timestamp\' => NULL,
      \'last_comment_uid\' => \'1\',
      \'log\' => \'\',
      \'menu\' => NULL,
      \'name\' => \'ntrive\',
      \'nid\' => NULL,
      \'node_export_drupal_version\' => \'7\',
      \'path\' => NULL,
      \'picture\' => \'0\',
      \'promote\' => \'0\',
      \'rdf_mapping\' => array(
        \'rdftype\' => array(
          \'sioc:Item\',
          \'foaf:Document\',
        ),
        \'title\' => array(
          \'predicates\' => array(
            \'dc:title\',
          ),
        ),
        \'created\' => array(
          \'predicates\' => array(
            \'dc:date\',
            \'dc:created\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'changed\' => array(
          \'predicates\' => array(
            \'dc:modified\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'body\' => array(
          \'predicates\' => array(
            \'content:encoded\',
          ),
        ),
        \'uid\' => array(
          \'predicates\' => array(
            \'sioc:has_creator\',
          ),
          \'type\' => \'rel\',
        ),
        \'name\' => array(
          \'predicates\' => array(
            \'foaf:name\',
          ),
        ),
        \'comment_count\' => array(
          \'predicates\' => array(
            \'sioc:num_replies\',
          ),
          \'datatype\' => \'xsd:integer\',
        ),
        \'last_activity\' => array(
          \'predicates\' => array(
            \'sioc:last_activity_date\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
      ),
      \'revision_timestamp\' => NULL,
      \'revision_uid\' => \'53\',
      \'status\' => \'1\',
      \'sticky\' => \'0\',
      \'title\' => \'Dialogue\',
      \'tnid\' => \'0\',
      \'translate\' => \'0\',
      \'type\' => \'activity_template\',
      \'uid\' => \'53\',
      \'uuid\' => \'0410acbe-bb17-4674-8fa6-3a4639f87d59\',
      \'vid\' => NULL,
      \'vuuid\' => \'7b5ca994-5ad1-4336-8431-575a9c4ac12d\',
    ),
  (object) array(
      \'changed\' => NULL,
      \'cid\' => \'0\',
      \'comment\' => \'1\',
      \'comment_count\' => \'0\',
      \'created\' => NULL,
      \'data\' => NULL,
      \'field_background\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'460\',
            \'uid\' => \'53\',
            \'filename\' => \'journey-maker-bg.jpg\',
            \'uri\' => \'public://journey-maker-bg.jpg\',
            \'filemime\' => \'image/jpeg\',
            \'filesize\' => \'46598\',
            \'status\' => \'1\',
            \'timestamp\' => \'1488476935\',
            \'uuid\' => \'e2e10305-0caf-4ae8-9003-fc82fd50cc2e\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1920\',
            \'height\' => \'1080\',
          ),
        ),
      ),
      \'field_background_image_url\' => array(),
      \'field_guide_cover_art\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'350\',
            \'uid\' => \'1\',
            \'filename\' => \'journey-guide-cover-creatures.png\',
            \'uri\' => \'public://journey-guide-cover-creatures.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'260045\',
            \'status\' => \'1\',
            \'timestamp\' => \'1466120002\',
            \'uuid\' => \'488eaab9-ba2e-4560-b5b9-aa2032cdf393\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1125\',
            \'height\' => \'1500\',
          ),
        ),
      ),
      \'field_guide_cover_art_home\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'443\',
            \'uid\' => \'1\',
            \'filename\' => \'home-journey-guide-cover-creatures.png\',
            \'uri\' => \'public://home-journey-guide-cover-creatures_0.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'556084\',
            \'status\' => \'1\',
            \'timestamp\' => \'1470432197\',
            \'uuid\' => \'e8f91fff-79d6-4048-8257-4a9cad62c1d6\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1125\',
            \'height\' => \'1500\',
          ),
        ),
      ),
      \'field_icon\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'268\',
            \'uid\' => \'1\',
            \'filename\' => \'strange_wild_creatures.png\',
            \'uri\' => \'public://strange_wild_creatures_0.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'4207\',
            \'status\' => \'1\',
            \'timestamp\' => \'1463761287\',
            \'uuid\' => \'d3b4e2ba-5105-4dad-b49b-038d039bd885\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'76\',
            \'height\' => \'76\',
          ),
        ),
      ),
      \'field_intro\' => array(
        \'und\' => array(
          array(
            \'value\' => \'Strange and wild creatures roam the Art Institute! Can you find them? Take a journey to find the scales, tails, jaws, and claws the museum holds. And then create your own fantastic creature!\',
            \'format\' => NULL,
            \'safe_value\' => \'Strange and wild creatures roam the Art Institute! Can you find them? Take a journey to find the scales, tails, jaws, and claws the museum holds. And then create your own fantastic creature!\',
          ),
        ),
      ),
      \'field_journey_guide_cover_title\' => array(
        \'und\' => array(
          array(
            \'value\' => \'CREATURE-FEATURED JOURNEY\',
            \'format\' => NULL,
            \'safe_value\' => \'CREATURE-FEATURED JOURNEY\',
          ),
        ),
      ),
      \'field_prompt\' => array(
        \'und\' => array(
          array(
            \'value\' => \'62\',
            \'revision_id\' => \'62\',
          ),
          array(
            \'value\' => \'63\',
            \'revision_id\' => \'63\',
          ),
          array(
            \'value\' => \'64\',
            \'revision_id\' => \'64\',
          ),
          array(
            \'value\' => \'65\',
            \'revision_id\' => \'65\',
          ),
          array(
            \'value\' => \'66\',
            \'revision_id\' => \'66\',
          ),
        ),
      ),
      \'field_shape_face\' => array(),
      \'files\' => array(),
      \'language\' => \'und\',
      \'last_comment_name\' => NULL,
      \'last_comment_timestamp\' => NULL,
      \'last_comment_uid\' => \'1\',
      \'log\' => \'\',
      \'menu\' => NULL,
      \'name\' => \'ntrive\',
      \'nid\' => NULL,
      \'node_export_drupal_version\' => \'7\',
      \'path\' => NULL,
      \'picture\' => \'0\',
      \'promote\' => \'0\',
      \'rdf_mapping\' => array(
        \'rdftype\' => array(
          \'sioc:Item\',
          \'foaf:Document\',
        ),
        \'title\' => array(
          \'predicates\' => array(
            \'dc:title\',
          ),
        ),
        \'created\' => array(
          \'predicates\' => array(
            \'dc:date\',
            \'dc:created\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'changed\' => array(
          \'predicates\' => array(
            \'dc:modified\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'body\' => array(
          \'predicates\' => array(
            \'content:encoded\',
          ),
        ),
        \'uid\' => array(
          \'predicates\' => array(
            \'sioc:has_creator\',
          ),
          \'type\' => \'rel\',
        ),
        \'name\' => array(
          \'predicates\' => array(
            \'foaf:name\',
          ),
        ),
        \'comment_count\' => array(
          \'predicates\' => array(
            \'sioc:num_replies\',
          ),
          \'datatype\' => \'xsd:integer\',
        ),
        \'last_activity\' => array(
          \'predicates\' => array(
            \'sioc:last_activity_date\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
      ),
      \'revision_timestamp\' => NULL,
      \'revision_uid\' => \'53\',
      \'status\' => \'1\',
      \'sticky\' => \'0\',
      \'title\' => \'Strange & Wild Creatures\',
      \'tnid\' => \'0\',
      \'translate\' => \'0\',
      \'type\' => \'aic_theme\',
      \'uid\' => \'53\',
      \'uuid\' => \'11554022-c48a-4b66-ad22-139673f9e011\',
      \'vid\' => NULL,
      \'vuuid\' => \'0cb08929-13e5-47e9-8e2f-7eaf19582eb9\',
    ),
  (object) array(
      \'changed\' => NULL,
      \'cid\' => \'0\',
      \'comment\' => \'1\',
      \'comment_count\' => \'0\',
      \'created\' => NULL,
      \'data\' => NULL,
      \'field_background\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'465\',
            \'uid\' => \'53\',
            \'filename\' => \'journey-maker-bg.jpg\',
            \'uri\' => \'public://journey-maker-bg.jpg\',
            \'filemime\' => \'image/jpeg\',
            \'filesize\' => \'46598\',
            \'status\' => \'1\',
            \'timestamp\' => \'1488477007\',
            \'uuid\' => \'94b4f190-dd95-440d-a680-1090aa9c6a98\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1920\',
            \'height\' => \'1080\',
          ),
        ),
      ),
      \'field_background_image_url\' => array(),
      \'field_guide_cover_art\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'351\',
            \'uid\' => \'1\',
            \'filename\' => \'journey-guide-cover-extreme.png\',
            \'uri\' => \'public://journey-guide-cover-extreme.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'167888\',
            \'status\' => \'1\',
            \'timestamp\' => \'1466120006\',
            \'uuid\' => \'2b67f06f-31d9-4f70-8124-4a7e16fc6d05\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1125\',
            \'height\' => \'1500\',
          ),
        ),
      ),
      \'field_guide_cover_art_home\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'436\',
            \'uid\' => \'1\',
            \'filename\' => \'home-journey-guide-cover-extreme.png\',
            \'uri\' => \'public://home-journey-guide-cover-extreme_0.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'399346\',
            \'status\' => \'1\',
            \'timestamp\' => \'1470426558\',
            \'uuid\' => \'d5f18d6e-1b13-4235-aa7d-b5ae538dbf9e\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1125\',
            \'height\' => \'1500\',
          ),
        ),
      ),
      \'field_icon\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'269\',
            \'uid\' => \'1\',
            \'filename\' => \'extreme_extraordinary.png\',
            \'uri\' => \'public://extreme_extraordinary.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'2780\',
            \'status\' => \'1\',
            \'timestamp\' => \'1463761313\',
            \'uuid\' => \'8a74bf54-718d-48f4-966c-86a3b5b844fa\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'76\',
            \'height\' => \'76\',
          ),
        ),
      ),
      \'field_intro\' => array(
        \'und\' => array(
          array(
            \'value\' => \'Take a journey to find the biggest, smallest, oldest, newest, heaviest, and strangest works of art in the Art Institute.\',
            \'format\' => NULL,
            \'safe_value\' => \'Take a journey to find the biggest, smallest, oldest, newest, heaviest, and strangest works of art in the Art Institute.\',
          ),
        ),
      ),
      \'field_journey_guide_cover_title\' => array(
        \'und\' => array(
          array(
            \'value\' => \'EXTREME JOURNEY\',
            \'format\' => NULL,
            \'safe_value\' => \'EXTREME JOURNEY\',
          ),
        ),
      ),
      \'field_prompt\' => array(
        \'und\' => array(
          array(
            \'value\' => \'47\',
            \'revision_id\' => \'47\',
          ),
          array(
            \'value\' => \'48\',
            \'revision_id\' => \'48\',
          ),
          array(
            \'value\' => \'49\',
            \'revision_id\' => \'49\',
          ),
          array(
            \'value\' => \'50\',
            \'revision_id\' => \'50\',
          ),
          array(
            \'value\' => \'51\',
            \'revision_id\' => \'51\',
          ),
        ),
      ),
      \'field_shape_face\' => array(),
      \'files\' => array(),
      \'language\' => \'und\',
      \'last_comment_name\' => NULL,
      \'last_comment_timestamp\' => NULL,
      \'last_comment_uid\' => \'1\',
      \'log\' => \'\',
      \'menu\' => NULL,
      \'name\' => \'ntrive\',
      \'nid\' => NULL,
      \'node_export_drupal_version\' => \'7\',
      \'path\' => NULL,
      \'picture\' => \'0\',
      \'promote\' => \'0\',
      \'rdf_mapping\' => array(
        \'rdftype\' => array(
          \'sioc:Item\',
          \'foaf:Document\',
        ),
        \'title\' => array(
          \'predicates\' => array(
            \'dc:title\',
          ),
        ),
        \'created\' => array(
          \'predicates\' => array(
            \'dc:date\',
            \'dc:created\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'changed\' => array(
          \'predicates\' => array(
            \'dc:modified\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'body\' => array(
          \'predicates\' => array(
            \'content:encoded\',
          ),
        ),
        \'uid\' => array(
          \'predicates\' => array(
            \'sioc:has_creator\',
          ),
          \'type\' => \'rel\',
        ),
        \'name\' => array(
          \'predicates\' => array(
            \'foaf:name\',
          ),
        ),
        \'comment_count\' => array(
          \'predicates\' => array(
            \'sioc:num_replies\',
          ),
          \'datatype\' => \'xsd:integer\',
        ),
        \'last_activity\' => array(
          \'predicates\' => array(
            \'sioc:last_activity_date\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
      ),
      \'revision_timestamp\' => NULL,
      \'revision_uid\' => \'53\',
      \'status\' => \'1\',
      \'sticky\' => \'0\',
      \'title\' => \'Extreme & Extraordinary\',
      \'tnid\' => \'0\',
      \'translate\' => \'0\',
      \'type\' => \'aic_theme\',
      \'uid\' => \'53\',
      \'uuid\' => \'129d7204-728e-4fd0-8804-50e3edea7514\',
      \'vid\' => NULL,
      \'vuuid\' => \'477ec6e7-aa11-4fd5-ab4c-f9566242acdc\',
    ),
  (object) array(
      \'changed\' => NULL,
      \'cid\' => \'0\',
      \'comment\' => \'1\',
      \'comment_count\' => \'0\',
      \'created\' => NULL,
      \'data\' => NULL,
      \'field_activity_image\' => array(),
      \'files\' => array(),
      \'language\' => \'und\',
      \'last_comment_name\' => NULL,
      \'last_comment_timestamp\' => NULL,
      \'last_comment_uid\' => \'1\',
      \'log\' => \'\',
      \'menu\' => NULL,
      \'name\' => \'ntrive\',
      \'nid\' => NULL,
      \'node_export_drupal_version\' => \'7\',
      \'path\' => NULL,
      \'picture\' => \'0\',
      \'promote\' => \'0\',
      \'rdf_mapping\' => array(
        \'rdftype\' => array(
          \'sioc:Item\',
          \'foaf:Document\',
        ),
        \'title\' => array(
          \'predicates\' => array(
            \'dc:title\',
          ),
        ),
        \'created\' => array(
          \'predicates\' => array(
            \'dc:date\',
            \'dc:created\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'changed\' => array(
          \'predicates\' => array(
            \'dc:modified\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'body\' => array(
          \'predicates\' => array(
            \'content:encoded\',
          ),
        ),
        \'uid\' => array(
          \'predicates\' => array(
            \'sioc:has_creator\',
          ),
          \'type\' => \'rel\',
        ),
        \'name\' => array(
          \'predicates\' => array(
            \'foaf:name\',
          ),
        ),
        \'comment_count\' => array(
          \'predicates\' => array(
            \'sioc:num_replies\',
          ),
          \'datatype\' => \'xsd:integer\',
        ),
        \'last_activity\' => array(
          \'predicates\' => array(
            \'sioc:last_activity_date\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
      ),
      \'revision_timestamp\' => NULL,
      \'revision_uid\' => \'53\',
      \'status\' => \'1\',
      \'sticky\' => \'0\',
      \'title\' => \'Pose\',
      \'tnid\' => \'0\',
      \'translate\' => \'0\',
      \'type\' => \'activity_template\',
      \'uid\' => \'53\',
      \'uuid\' => \'1d8781dc-e843-4eac-8dd7-54cb0a6711c7\',
      \'vid\' => NULL,
      \'vuuid\' => \'455807c4-0b80-4f55-8087-341d3e54e0ff\',
    ),
  (object) array(
      \'changed\' => NULL,
      \'cid\' => \'0\',
      \'comment\' => \'1\',
      \'comment_count\' => \'0\',
      \'created\' => NULL,
      \'data\' => NULL,
      \'field_activity_image\' => array(),
      \'files\' => array(),
      \'language\' => \'und\',
      \'last_comment_name\' => NULL,
      \'last_comment_timestamp\' => NULL,
      \'last_comment_uid\' => \'1\',
      \'log\' => \'\',
      \'menu\' => NULL,
      \'name\' => \'ntrive\',
      \'nid\' => NULL,
      \'node_export_drupal_version\' => \'7\',
      \'path\' => NULL,
      \'picture\' => \'0\',
      \'promote\' => \'0\',
      \'rdf_mapping\' => array(
        \'rdftype\' => array(
          \'sioc:Item\',
          \'foaf:Document\',
        ),
        \'title\' => array(
          \'predicates\' => array(
            \'dc:title\',
          ),
        ),
        \'created\' => array(
          \'predicates\' => array(
            \'dc:date\',
            \'dc:created\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'changed\' => array(
          \'predicates\' => array(
            \'dc:modified\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'body\' => array(
          \'predicates\' => array(
            \'content:encoded\',
          ),
        ),
        \'uid\' => array(
          \'predicates\' => array(
            \'sioc:has_creator\',
          ),
          \'type\' => \'rel\',
        ),
        \'name\' => array(
          \'predicates\' => array(
            \'foaf:name\',
          ),
        ),
        \'comment_count\' => array(
          \'predicates\' => array(
            \'sioc:num_replies\',
          ),
          \'datatype\' => \'xsd:integer\',
        ),
        \'last_activity\' => array(
          \'predicates\' => array(
            \'sioc:last_activity_date\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
      ),
      \'revision_timestamp\' => NULL,
      \'revision_uid\' => \'53\',
      \'status\' => \'1\',
      \'sticky\' => \'0\',
      \'title\' => \'Sequence\',
      \'tnid\' => \'0\',
      \'translate\' => \'0\',
      \'type\' => \'activity_template\',
      \'uid\' => \'53\',
      \'uuid\' => \'1f6eabbf-dc75-40dc-b0c5-4e61b8709437\',
      \'vid\' => NULL,
      \'vuuid\' => \'b921363d-c710-4bdf-8ada-cc4543561c54\',
    ),
  (object) array(
      \'changed\' => NULL,
      \'cid\' => \'0\',
      \'comment\' => \'1\',
      \'comment_count\' => \'0\',
      \'created\' => NULL,
      \'data\' => NULL,
      \'field_background\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'464\',
            \'uid\' => \'53\',
            \'filename\' => \'journey-maker-bg.jpg\',
            \'uri\' => \'public://journey-maker-bg.jpg\',
            \'filemime\' => \'image/jpeg\',
            \'filesize\' => \'46598\',
            \'status\' => \'1\',
            \'timestamp\' => \'1488476991\',
            \'uuid\' => \'850159f4-d903-470a-81f2-d410cecc008d\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1920\',
            \'height\' => \'1080\',
          ),
        ),
      ),
      \'field_background_image_url\' => array(),
      \'field_guide_cover_art\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'353\',
            \'uid\' => \'1\',
            \'filename\' => \'journey-guide-cover-games.png\',
            \'uri\' => \'public://journey-guide-cover-games.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'263673\',
            \'status\' => \'1\',
            \'timestamp\' => \'1466120013\',
            \'uuid\' => \'402161e1-bee8-4ef2-9c7c-7de9b428c6fa\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1125\',
            \'height\' => \'1500\',
          ),
        ),
      ),
      \'field_guide_cover_art_home\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'437\',
            \'uid\' => \'1\',
            \'filename\' => \'home-journey-guide-cover-games.png\',
            \'uri\' => \'public://home-journey-guide-cover-games_0.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'554240\',
            \'status\' => \'1\',
            \'timestamp\' => \'1470430327\',
            \'uuid\' => \'be40132f-a2a0-49ac-be15-fad73092940c\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1125\',
            \'height\' => \'1500\',
          ),
        ),
      ),
      \'field_icon\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'271\',
            \'uid\' => \'1\',
            \'filename\' => \'lets_play.png\',
            \'uri\' => \'public://lets_play.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'2315\',
            \'status\' => \'1\',
            \'timestamp\' => \'1463761401\',
            \'uuid\' => \'b890a479-b784-4e9a-92a8-24edd0c8b5aa\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'76\',
            \'height\' => \'77\',
          ),
        ),
      ),
      \'field_intro\' => array(
        \'und\' => array(
          array(
            \'value\' => \'Games in the museum? Absolutely! Team up with your family for a PLAYFUL journey with works of art.\',
            \'format\' => NULL,
            \'safe_value\' => \'Games in the museum? Absolutely! Team up with your family for a PLAYFUL journey with works of art.\',
          ),
        ),
      ),
      \'field_journey_guide_cover_title\' => array(
        \'und\' => array(
          array(
            \'value\' => \'GAME-ON JOURNEY\',
            \'format\' => NULL,
            \'safe_value\' => \'GAME-ON JOURNEY\',
          ),
        ),
      ),
      \'field_prompt\' => array(
        \'und\' => array(
          array(
            \'value\' => \'52\',
            \'revision_id\' => \'52\',
          ),
          array(
            \'value\' => \'53\',
            \'revision_id\' => \'53\',
          ),
          array(
            \'value\' => \'54\',
            \'revision_id\' => \'54\',
          ),
          array(
            \'value\' => \'55\',
            \'revision_id\' => \'55\',
          ),
          array(
            \'value\' => \'56\',
            \'revision_id\' => \'56\',
          ),
        ),
      ),
      \'field_shape_face\' => array(),
      \'files\' => array(),
      \'language\' => \'und\',
      \'last_comment_name\' => NULL,
      \'last_comment_timestamp\' => NULL,
      \'last_comment_uid\' => \'1\',
      \'log\' => \'\',
      \'menu\' => NULL,
      \'name\' => \'ntrive\',
      \'nid\' => NULL,
      \'node_export_drupal_version\' => \'7\',
      \'path\' => NULL,
      \'picture\' => \'0\',
      \'promote\' => \'0\',
      \'rdf_mapping\' => array(
        \'rdftype\' => array(
          \'sioc:Item\',
          \'foaf:Document\',
        ),
        \'title\' => array(
          \'predicates\' => array(
            \'dc:title\',
          ),
        ),
        \'created\' => array(
          \'predicates\' => array(
            \'dc:date\',
            \'dc:created\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'changed\' => array(
          \'predicates\' => array(
            \'dc:modified\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'body\' => array(
          \'predicates\' => array(
            \'content:encoded\',
          ),
        ),
        \'uid\' => array(
          \'predicates\' => array(
            \'sioc:has_creator\',
          ),
          \'type\' => \'rel\',
        ),
        \'name\' => array(
          \'predicates\' => array(
            \'foaf:name\',
          ),
        ),
        \'comment_count\' => array(
          \'predicates\' => array(
            \'sioc:num_replies\',
          ),
          \'datatype\' => \'xsd:integer\',
        ),
        \'last_activity\' => array(
          \'predicates\' => array(
            \'sioc:last_activity_date\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
      ),
      \'revision_timestamp\' => NULL,
      \'revision_uid\' => \'53\',
      \'status\' => \'1\',
      \'sticky\' => \'0\',
      \'title\' => \'Let’s Play\',
      \'tnid\' => \'0\',
      \'translate\' => \'0\',
      \'type\' => \'aic_theme\',
      \'uid\' => \'53\',
      \'uuid\' => \'3dc3d9af-4394-4a0d-a8fb-18160fac4d3f\',
      \'vid\' => NULL,
      \'vuuid\' => \'84d6f712-20fb-44c4-b0cd-6a5c7b139ebf\',
    ),
  (object) array(
      \'changed\' => NULL,
      \'cid\' => \'0\',
      \'comment\' => \'1\',
      \'comment_count\' => \'0\',
      \'created\' => NULL,
      \'data\' => NULL,
      \'field_background\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'461\',
            \'uid\' => \'53\',
            \'filename\' => \'journey-maker-bg.jpg\',
            \'uri\' => \'public://journey-maker-bg.jpg\',
            \'filemime\' => \'image/jpeg\',
            \'filesize\' => \'46598\',
            \'status\' => \'1\',
            \'timestamp\' => \'1488476949\',
            \'uuid\' => \'2965b8a5-6db3-4227-ac54-d45b71aea792\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1920\',
            \'height\' => \'1080\',
          ),
        ),
      ),
      \'field_background_image_url\' => array(),
      \'field_guide_cover_art\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'348\',
            \'uid\' => \'1\',
            \'filename\' => \'journey-guide-cover-shapes.png\',
            \'uri\' => \'public://journey-guide-cover-shapes.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'223061\',
            \'status\' => \'1\',
            \'timestamp\' => \'1466119993\',
            \'uuid\' => \'fc0cb326-f10e-43c8-9223-7b701b65ccff\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1125\',
            \'height\' => \'1500\',
          ),
        ),
      ),
      \'field_guide_cover_art_home\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'441\',
            \'uid\' => \'1\',
            \'filename\' => \'home-journey-guide-cover-shapes.png\',
            \'uri\' => \'public://home-journey-guide-cover-shapes.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'539950\',
            \'status\' => \'1\',
            \'timestamp\' => \'1470430470\',
            \'uuid\' => \'040cf7b1-5d32-4231-a447-ae7f75cbdf41\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1125\',
            \'height\' => \'1500\',
          ),
        ),
      ),
      \'field_icon\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'344\',
            \'uid\' => \'1\',
            \'filename\' => \'line_shape_color.png\',
            \'uri\' => \'public://line_shape_color.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'4697\',
            \'status\' => \'1\',
            \'timestamp\' => \'1466022165\',
            \'uuid\' => \'8c3e3d68-846b-4405-8219-3fee3f77a858\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'76\',
            \'height\' => \'76\',
          ),
        ),
      ),
      \'field_intro\' => array(
        \'und\' => array(
          array(
            \'value\' => \'A dot, a dash, a squiggle, a square. Colors and patterns are everywhere!\',
            \'format\' => NULL,
            \'safe_value\' => \'A dot, a dash, a squiggle, a square. Colors and patterns are everywhere!\',
          ),
        ),
      ),
      \'field_journey_guide_cover_title\' => array(
        \'und\' => array(
          array(
            \'value\' => \'ARTSY JOURNEY\',
            \'format\' => NULL,
            \'safe_value\' => \'ARTSY JOURNEY\',
          ),
        ),
      ),
      \'field_prompt\' => array(
        \'und\' => array(
          array(
            \'value\' => \'77\',
            \'revision_id\' => \'77\',
          ),
          array(
            \'value\' => \'78\',
            \'revision_id\' => \'78\',
          ),
          array(
            \'value\' => \'79\',
            \'revision_id\' => \'79\',
          ),
          array(
            \'value\' => \'80\',
            \'revision_id\' => \'80\',
          ),
          array(
            \'value\' => \'81\',
            \'revision_id\' => \'81\',
          ),
        ),
      ),
      \'field_shape_face\' => array(),
      \'files\' => array(),
      \'language\' => \'und\',
      \'last_comment_name\' => NULL,
      \'last_comment_timestamp\' => NULL,
      \'last_comment_uid\' => \'1\',
      \'log\' => \'\',
      \'menu\' => NULL,
      \'name\' => \'ntrive\',
      \'nid\' => NULL,
      \'node_export_drupal_version\' => \'7\',
      \'path\' => NULL,
      \'picture\' => \'0\',
      \'promote\' => \'0\',
      \'rdf_mapping\' => array(
        \'rdftype\' => array(
          \'sioc:Item\',
          \'foaf:Document\',
        ),
        \'title\' => array(
          \'predicates\' => array(
            \'dc:title\',
          ),
        ),
        \'created\' => array(
          \'predicates\' => array(
            \'dc:date\',
            \'dc:created\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'changed\' => array(
          \'predicates\' => array(
            \'dc:modified\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'body\' => array(
          \'predicates\' => array(
            \'content:encoded\',
          ),
        ),
        \'uid\' => array(
          \'predicates\' => array(
            \'sioc:has_creator\',
          ),
          \'type\' => \'rel\',
        ),
        \'name\' => array(
          \'predicates\' => array(
            \'foaf:name\',
          ),
        ),
        \'comment_count\' => array(
          \'predicates\' => array(
            \'sioc:num_replies\',
          ),
          \'datatype\' => \'xsd:integer\',
        ),
        \'last_activity\' => array(
          \'predicates\' => array(
            \'sioc:last_activity_date\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
      ),
      \'revision_timestamp\' => NULL,
      \'revision_uid\' => \'53\',
      \'status\' => \'1\',
      \'sticky\' => \'0\',
      \'title\' => \'Lines, Shapes, Colors\',
      \'tnid\' => \'0\',
      \'translate\' => \'0\',
      \'type\' => \'aic_theme\',
      \'uid\' => \'53\',
      \'uuid\' => \'415fdd62-5600-45f5-beda-ff5ac147f77a\',
      \'vid\' => NULL,
      \'vuuid\' => \'602a6968-03a9-43d2-a8af-b7f96a2ab046\',
    ),
  (object) array(
      \'changed\' => NULL,
      \'cid\' => \'0\',
      \'comment\' => \'1\',
      \'comment_count\' => \'0\',
      \'created\' => NULL,
      \'data\' => NULL,
      \'field_background\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'458\',
            \'uid\' => \'53\',
            \'filename\' => \'journey-maker-bg.jpg\',
            \'uri\' => \'public://journey-maker-bg.jpg\',
            \'filemime\' => \'image/jpeg\',
            \'filesize\' => \'46598\',
            \'status\' => \'1\',
            \'timestamp\' => \'1488476902\',
            \'uuid\' => \'820ab9a5-56a6-4859-9b68-a5c3914f6192\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1920\',
            \'height\' => \'1080\',
          ),
        ),
      ),
      \'field_background_image_url\' => array(),
      \'field_guide_cover_art\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'352\',
            \'uid\' => \'1\',
            \'filename\' => \'journey-guide-cover-sleepover.png\',
            \'uri\' => \'public://journey-guide-cover-sleepover.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'149342\',
            \'status\' => \'1\',
            \'timestamp\' => \'1466120009\',
            \'uuid\' => \'55b5a4fd-6712-4004-be3f-e2f334ae9350\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1125\',
            \'height\' => \'1500\',
          ),
        ),
      ),
      \'field_guide_cover_art_home\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'435\',
            \'uid\' => \'1\',
            \'filename\' => \'home-journey-guide-cover-sleepover.png\',
            \'uri\' => \'public://home-journey-guide-cover-sleepover.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'359186\',
            \'status\' => \'1\',
            \'timestamp\' => \'1470426529\',
            \'uuid\' => \'94ff9d92-2d7f-4b55-b850-0960d3790ae1\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1125\',
            \'height\' => \'1500\',
          ),
        ),
      ),
      \'field_icon\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'270\',
            \'uid\' => \'1\',
            \'filename\' => \'museum_sleepover.png\',
            \'uri\' => \'public://museum_sleepover.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'3172\',
            \'status\' => \'1\',
            \'timestamp\' => \'1463761341\',
            \'uuid\' => \'9fa33155-ab26-485b-8b79-41557263a269\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'76\',
            \'height\' => \'77\',
          ),
        ),
      ),
      \'field_intro\' => array(
        \'und\' => array(
          array(
            \'value\' => \'Spend a night at the museum! Pick out a late-night activity, stake out the best spot for a nap, grab snacks, play games, and gather your museum buds for the ultimate sleepover!\',
            \'format\' => NULL,
            \'safe_value\' => \'Spend a night at the museum! Pick out a late-night activity, stake out the best spot for a nap, grab snacks, play games, and gather your museum buds for the ultimate sleepover!\',
          ),
        ),
      ),
      \'field_journey_guide_cover_title\' => array(
        \'und\' => array(
          array(
            \'value\' => \'LATE-NIGHT JOURNEY\',
            \'format\' => NULL,
            \'safe_value\' => \'LATE-NIGHT JOURNEY\',
          ),
        ),
      ),
      \'field_prompt\' => array(
        \'und\' => array(
          array(
            \'value\' => \'57\',
            \'revision_id\' => \'57\',
          ),
          array(
            \'value\' => \'58\',
            \'revision_id\' => \'58\',
          ),
          array(
            \'value\' => \'59\',
            \'revision_id\' => \'59\',
          ),
          array(
            \'value\' => \'60\',
            \'revision_id\' => \'60\',
          ),
          array(
            \'value\' => \'61\',
            \'revision_id\' => \'61\',
          ),
        ),
      ),
      \'field_shape_face\' => array(),
      \'files\' => array(),
      \'language\' => \'und\',
      \'last_comment_name\' => NULL,
      \'last_comment_timestamp\' => NULL,
      \'last_comment_uid\' => \'1\',
      \'log\' => \'\',
      \'menu\' => NULL,
      \'name\' => \'ntrive\',
      \'nid\' => NULL,
      \'node_export_drupal_version\' => \'7\',
      \'path\' => NULL,
      \'picture\' => \'0\',
      \'promote\' => \'0\',
      \'rdf_mapping\' => array(
        \'rdftype\' => array(
          \'sioc:Item\',
          \'foaf:Document\',
        ),
        \'title\' => array(
          \'predicates\' => array(
            \'dc:title\',
          ),
        ),
        \'created\' => array(
          \'predicates\' => array(
            \'dc:date\',
            \'dc:created\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'changed\' => array(
          \'predicates\' => array(
            \'dc:modified\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'body\' => array(
          \'predicates\' => array(
            \'content:encoded\',
          ),
        ),
        \'uid\' => array(
          \'predicates\' => array(
            \'sioc:has_creator\',
          ),
          \'type\' => \'rel\',
        ),
        \'name\' => array(
          \'predicates\' => array(
            \'foaf:name\',
          ),
        ),
        \'comment_count\' => array(
          \'predicates\' => array(
            \'sioc:num_replies\',
          ),
          \'datatype\' => \'xsd:integer\',
        ),
        \'last_activity\' => array(
          \'predicates\' => array(
            \'sioc:last_activity_date\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
      ),
      \'revision_timestamp\' => NULL,
      \'revision_uid\' => \'53\',
      \'status\' => \'1\',
      \'sticky\' => \'0\',
      \'title\' => \'Museum Sleepover\',
      \'tnid\' => \'0\',
      \'translate\' => \'0\',
      \'type\' => \'aic_theme\',
      \'uid\' => \'53\',
      \'uuid\' => \'5a30a458-ffbd-4aa4-8a53-ed18ba297e19\',
      \'vid\' => NULL,
      \'vuuid\' => \'1e8c8a90-f89d-4f2b-9005-2d2a1ae4ed4e\',
    ),
  (object) array(
      \'changed\' => NULL,
      \'cid\' => \'0\',
      \'comment\' => \'1\',
      \'comment_count\' => \'0\',
      \'created\' => NULL,
      \'data\' => NULL,
      \'field_activity_image\' => array(),
      \'files\' => array(),
      \'language\' => \'und\',
      \'last_comment_name\' => NULL,
      \'last_comment_timestamp\' => NULL,
      \'last_comment_uid\' => \'1\',
      \'log\' => \'\',
      \'menu\' => NULL,
      \'name\' => \'ntrive\',
      \'nid\' => NULL,
      \'node_export_drupal_version\' => \'7\',
      \'path\' => NULL,
      \'picture\' => \'0\',
      \'promote\' => \'0\',
      \'rdf_mapping\' => array(
        \'rdftype\' => array(
          \'sioc:Item\',
          \'foaf:Document\',
        ),
        \'title\' => array(
          \'predicates\' => array(
            \'dc:title\',
          ),
        ),
        \'created\' => array(
          \'predicates\' => array(
            \'dc:date\',
            \'dc:created\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'changed\' => array(
          \'predicates\' => array(
            \'dc:modified\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'body\' => array(
          \'predicates\' => array(
            \'content:encoded\',
          ),
        ),
        \'uid\' => array(
          \'predicates\' => array(
            \'sioc:has_creator\',
          ),
          \'type\' => \'rel\',
        ),
        \'name\' => array(
          \'predicates\' => array(
            \'foaf:name\',
          ),
        ),
        \'comment_count\' => array(
          \'predicates\' => array(
            \'sioc:num_replies\',
          ),
          \'datatype\' => \'xsd:integer\',
        ),
        \'last_activity\' => array(
          \'predicates\' => array(
            \'sioc:last_activity_date\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
      ),
      \'revision_timestamp\' => NULL,
      \'revision_uid\' => \'53\',
      \'status\' => \'1\',
      \'sticky\' => \'0\',
      \'title\' => \'Verbal Response\',
      \'tnid\' => \'0\',
      \'translate\' => \'0\',
      \'type\' => \'activity_template\',
      \'uid\' => \'53\',
      \'uuid\' => \'63fa47f5-5474-4fd7-bbb3-192002e29840\',
      \'vid\' => NULL,
      \'vuuid\' => \'683518c3-8e1d-42d0-98c1-72dbe21851e7\',
    ),
  (object) array(
      \'changed\' => NULL,
      \'cid\' => \'0\',
      \'comment\' => \'1\',
      \'comment_count\' => \'0\',
      \'created\' => NULL,
      \'data\' => NULL,
      \'field_background\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'462\',
            \'uid\' => \'53\',
            \'filename\' => \'journey-maker-bg.jpg\',
            \'uri\' => \'public://journey-maker-bg.jpg\',
            \'filemime\' => \'image/jpeg\',
            \'filesize\' => \'46598\',
            \'status\' => \'1\',
            \'timestamp\' => \'1488476962\',
            \'uuid\' => \'ad399b71-99c6-42f5-b63d-4c4c6b591dc6\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1920\',
            \'height\' => \'1080\',
          ),
        ),
      ),
      \'field_background_image_url\' => array(),
      \'field_guide_cover_art\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'354\',
            \'uid\' => \'1\',
            \'filename\' => \'journey-guide-cover-superheroes.png\',
            \'uri\' => \'public://journey-guide-cover-superheroes.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'174158\',
            \'status\' => \'1\',
            \'timestamp\' => \'1466120016\',
            \'uuid\' => \'3048298f-9f14-478f-9a4b-d436848b0672\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1125\',
            \'height\' => \'1500\',
          ),
        ),
      ),
      \'field_guide_cover_art_home\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'439\',
            \'uid\' => \'1\',
            \'filename\' => \'home-journey-guide-cover-superheroes.png\',
            \'uri\' => \'public://home-journey-guide-cover-superheroes.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'439126\',
            \'status\' => \'1\',
            \'timestamp\' => \'1470430440\',
            \'uuid\' => \'a99a05a3-d2d5-469c-b13e-2db548d2abc3\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1125\',
            \'height\' => \'1500\',
          ),
        ),
      ),
      \'field_icon\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'272\',
            \'uid\' => \'1\',
            \'filename\' => \'superheroes.png\',
            \'uri\' => \'public://superheroes_0.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'3779\',
            \'status\' => \'1\',
            \'timestamp\' => \'1463761434\',
            \'uuid\' => \'b410b152-94c2-4f14-8e92-df0089b6f128\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'76\',
            \'height\' => \'77\',
          ),
        ),
      ),
      \'field_intro\' => array(
        \'und\' => array(
          array(
            \'value\' => \'It’s a bird! It’s a plane! It’s a super adventure for you and your family! Don’t forget your cape!\',
            \'format\' => NULL,
            \'safe_value\' => \'It’s a bird! It’s a plane! It’s a super adventure for you and your family! Don’t forget your cape!\',
          ),
        ),
      ),
      \'field_journey_guide_cover_title\' => array(
        \'und\' => array(
          array(
            \'value\' => \'SUPER JOURNEY\',
            \'format\' => NULL,
            \'safe_value\' => \'SUPER JOURNEY\',
          ),
        ),
      ),
      \'field_prompt\' => array(
        \'und\' => array(
          array(
            \'value\' => \'67\',
            \'revision_id\' => \'67\',
          ),
          array(
            \'value\' => \'68\',
            \'revision_id\' => \'68\',
          ),
          array(
            \'value\' => \'69\',
            \'revision_id\' => \'69\',
          ),
          array(
            \'value\' => \'70\',
            \'revision_id\' => \'70\',
          ),
          array(
            \'value\' => \'71\',
            \'revision_id\' => \'71\',
          ),
        ),
      ),
      \'field_shape_face\' => array(),
      \'files\' => array(),
      \'language\' => \'und\',
      \'last_comment_name\' => NULL,
      \'last_comment_timestamp\' => NULL,
      \'last_comment_uid\' => \'1\',
      \'log\' => \'\',
      \'menu\' => NULL,
      \'name\' => \'ntrive\',
      \'nid\' => NULL,
      \'node_export_drupal_version\' => \'7\',
      \'path\' => NULL,
      \'picture\' => \'0\',
      \'promote\' => \'0\',
      \'rdf_mapping\' => array(
        \'rdftype\' => array(
          \'sioc:Item\',
          \'foaf:Document\',
        ),
        \'title\' => array(
          \'predicates\' => array(
            \'dc:title\',
          ),
        ),
        \'created\' => array(
          \'predicates\' => array(
            \'dc:date\',
            \'dc:created\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'changed\' => array(
          \'predicates\' => array(
            \'dc:modified\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'body\' => array(
          \'predicates\' => array(
            \'content:encoded\',
          ),
        ),
        \'uid\' => array(
          \'predicates\' => array(
            \'sioc:has_creator\',
          ),
          \'type\' => \'rel\',
        ),
        \'name\' => array(
          \'predicates\' => array(
            \'foaf:name\',
          ),
        ),
        \'comment_count\' => array(
          \'predicates\' => array(
            \'sioc:num_replies\',
          ),
          \'datatype\' => \'xsd:integer\',
        ),
        \'last_activity\' => array(
          \'predicates\' => array(
            \'sioc:last_activity_date\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
      ),
      \'revision_timestamp\' => NULL,
      \'revision_uid\' => \'53\',
      \'status\' => \'1\',
      \'sticky\' => \'0\',
      \'title\' => \'SuperHEROES\',
      \'tnid\' => \'0\',
      \'translate\' => \'0\',
      \'type\' => \'aic_theme\',
      \'uid\' => \'53\',
      \'uuid\' => \'891f8990-473b-4804-8945-5e348b33e9d5\',
      \'vid\' => NULL,
      \'vuuid\' => \'43afae0c-84ba-4aab-8718-ef159ce07b4d\',
    ),
  (object) array(
      \'changed\' => NULL,
      \'cid\' => \'0\',
      \'comment\' => \'1\',
      \'comment_count\' => \'0\',
      \'created\' => NULL,
      \'data\' => NULL,
      \'field_activity_image\' => array(),
      \'files\' => array(),
      \'language\' => \'und\',
      \'last_comment_name\' => NULL,
      \'last_comment_timestamp\' => NULL,
      \'last_comment_uid\' => \'1\',
      \'log\' => \'\',
      \'menu\' => NULL,
      \'name\' => \'ntrive\',
      \'nid\' => NULL,
      \'node_export_drupal_version\' => \'7\',
      \'path\' => NULL,
      \'picture\' => \'0\',
      \'promote\' => \'0\',
      \'rdf_mapping\' => array(
        \'rdftype\' => array(
          \'sioc:Item\',
          \'foaf:Document\',
        ),
        \'title\' => array(
          \'predicates\' => array(
            \'dc:title\',
          ),
        ),
        \'created\' => array(
          \'predicates\' => array(
            \'dc:date\',
            \'dc:created\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'changed\' => array(
          \'predicates\' => array(
            \'dc:modified\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'body\' => array(
          \'predicates\' => array(
            \'content:encoded\',
          ),
        ),
        \'uid\' => array(
          \'predicates\' => array(
            \'sioc:has_creator\',
          ),
          \'type\' => \'rel\',
        ),
        \'name\' => array(
          \'predicates\' => array(
            \'foaf:name\',
          ),
        ),
        \'comment_count\' => array(
          \'predicates\' => array(
            \'sioc:num_replies\',
          ),
          \'datatype\' => \'xsd:integer\',
        ),
        \'last_activity\' => array(
          \'predicates\' => array(
            \'sioc:last_activity_date\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
      ),
      \'revision_timestamp\' => NULL,
      \'revision_uid\' => \'53\',
      \'status\' => \'1\',
      \'sticky\' => \'0\',
      \'title\' => \'Writing & Drawing\',
      \'tnid\' => \'0\',
      \'translate\' => \'0\',
      \'type\' => \'activity_template\',
      \'uid\' => \'53\',
      \'uuid\' => \'948e5c0b-4908-49b3-9940-5714132f1fe7\',
      \'vid\' => NULL,
      \'vuuid\' => \'fe0dfa67-6ef6-474c-99b6-bd4c29957fbc\',
    ),
  (object) array(
      \'changed\' => NULL,
      \'cid\' => \'0\',
      \'comment\' => \'1\',
      \'comment_count\' => \'0\',
      \'created\' => NULL,
      \'data\' => NULL,
      \'field_background\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'459\',
            \'uid\' => \'53\',
            \'filename\' => \'journey-maker-bg.jpg\',
            \'uri\' => \'public://journey-maker-bg.jpg\',
            \'filemime\' => \'image/jpeg\',
            \'filesize\' => \'46598\',
            \'status\' => \'1\',
            \'timestamp\' => \'1488476919\',
            \'uuid\' => \'51f6322b-ec57-4901-95ee-670b014b8433\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1920\',
            \'height\' => \'1080\',
          ),
        ),
      ),
      \'field_background_image_url\' => array(),
      \'field_guide_cover_art\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'349\',
            \'uid\' => \'1\',
            \'filename\' => \'journey-guide-cover-fashion.png\',
            \'uri\' => \'public://journey-guide-cover-fashion.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'160694\',
            \'status\' => \'1\',
            \'timestamp\' => \'1466119998\',
            \'uuid\' => \'3a9de4de-c440-421a-ae20-07f0ccd7823a\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1125\',
            \'height\' => \'1500\',
          ),
        ),
      ),
      \'field_guide_cover_art_home\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'440\',
            \'uid\' => \'1\',
            \'filename\' => \'home-journey-guide-cover-fashion.png\',
            \'uri\' => \'public://home-journey-guide-cover-fashion.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'382527\',
            \'status\' => \'1\',
            \'timestamp\' => \'1470430454\',
            \'uuid\' => \'9ab9c698-b785-427b-80c8-7a5014ba2159\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1125\',
            \'height\' => \'1500\',
          ),
        ),
      ),
      \'field_icon\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'346\',
            \'uid\' => \'1\',
            \'filename\' => \'wild_style.png\',
            \'uri\' => \'public://wild_style.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'1923\',
            \'status\' => \'1\',
            \'timestamp\' => \'1466023289\',
            \'uuid\' => \'be491d4f-e5e2-46ce-a6bb-d0acd505ae62\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'76\',
            \'height\' => \'76\',
          ),
        ),
      ),
      \'field_intro\' => array(
        \'und\' => array(
          array(
            \'value\' => \'A cabinet of curiosities, a closet of treasures. Let your imagination run wild and put a fantastic costume together.\',
            \'format\' => NULL,
            \'safe_value\' => \'A cabinet of curiosities, a closet of treasures. Let your imagination run wild and put a fantastic costume together.\',
          ),
        ),
      ),
      \'field_journey_guide_cover_title\' => array(
        \'und\' => array(
          array(
            \'value\' => \'STYLISH JOURNEY\',
            \'format\' => NULL,
            \'safe_value\' => \'STYLISH JOURNEY\',
          ),
        ),
      ),
      \'field_prompt\' => array(
        \'und\' => array(
          array(
            \'value\' => \'82\',
            \'revision_id\' => \'92\',
          ),
          array(
            \'value\' => \'83\',
            \'revision_id\' => \'93\',
          ),
          array(
            \'value\' => \'84\',
            \'revision_id\' => \'94\',
          ),
          array(
            \'value\' => \'85\',
            \'revision_id\' => \'95\',
          ),
          array(
            \'value\' => \'86\',
            \'revision_id\' => \'96\',
          ),
        ),
      ),
      \'field_shape_face\' => array(),
      \'files\' => array(),
      \'language\' => \'und\',
      \'last_comment_name\' => NULL,
      \'last_comment_timestamp\' => NULL,
      \'last_comment_uid\' => \'1\',
      \'log\' => \'test\',
      \'menu\' => NULL,
      \'name\' => \'ntrive\',
      \'nid\' => NULL,
      \'node_export_drupal_version\' => \'7\',
      \'path\' => NULL,
      \'picture\' => \'0\',
      \'promote\' => \'0\',
      \'rdf_mapping\' => array(
        \'rdftype\' => array(
          \'sioc:Item\',
          \'foaf:Document\',
        ),
        \'title\' => array(
          \'predicates\' => array(
            \'dc:title\',
          ),
        ),
        \'created\' => array(
          \'predicates\' => array(
            \'dc:date\',
            \'dc:created\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'changed\' => array(
          \'predicates\' => array(
            \'dc:modified\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'body\' => array(
          \'predicates\' => array(
            \'content:encoded\',
          ),
        ),
        \'uid\' => array(
          \'predicates\' => array(
            \'sioc:has_creator\',
          ),
          \'type\' => \'rel\',
        ),
        \'name\' => array(
          \'predicates\' => array(
            \'foaf:name\',
          ),
        ),
        \'comment_count\' => array(
          \'predicates\' => array(
            \'sioc:num_replies\',
          ),
          \'datatype\' => \'xsd:integer\',
        ),
        \'last_activity\' => array(
          \'predicates\' => array(
            \'sioc:last_activity_date\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
      ),
      \'revision_timestamp\' => NULL,
      \'revision_uid\' => \'53\',
      \'status\' => \'1\',
      \'sticky\' => \'0\',
      \'title\' => \'Wild Style \',
      \'tnid\' => \'0\',
      \'translate\' => \'0\',
      \'type\' => \'aic_theme\',
      \'uid\' => \'53\',
      \'uuid\' => \'cd1fdcca-8545-42d5-9e09-2790fca82e81\',
      \'vid\' => NULL,
      \'vuuid\' => \'d4fd984c-d7d8-4896-916e-5b85e44c3431\',
    ),
  (object) array(
      \'changed\' => NULL,
      \'cid\' => \'0\',
      \'comment\' => \'1\',
      \'comment_count\' => \'0\',
      \'created\' => NULL,
      \'data\' => NULL,
      \'field_background\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'463\',
            \'uid\' => \'53\',
            \'filename\' => \'journey-maker-bg.jpg\',
            \'uri\' => \'public://journey-maker-bg.jpg\',
            \'filemime\' => \'image/jpeg\',
            \'filesize\' => \'46598\',
            \'status\' => \'1\',
            \'timestamp\' => \'1488476977\',
            \'uuid\' => \'1ae77852-a603-4cac-b4d5-c8acf88c2fc2\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1920\',
            \'height\' => \'1080\',
          ),
        ),
      ),
      \'field_background_image_url\' => array(),
      \'field_guide_cover_art\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'355\',
            \'uid\' => \'1\',
            \'filename\' => \'journey-guide-cover-time.png\',
            \'uri\' => \'public://journey-guide-cover-time.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'200389\',
            \'status\' => \'1\',
            \'timestamp\' => \'1466120020\',
            \'uuid\' => \'7128d22e-5305-40bf-a1fb-67717fd5a546\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1125\',
            \'height\' => \'1500\',
          ),
        ),
      ),
      \'field_guide_cover_art_home\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'438\',
            \'uid\' => \'1\',
            \'filename\' => \'home-journey-guide-cover-time.png\',
            \'uri\' => \'public://home-journey-guide-cover-time_0.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'472603\',
            \'status\' => \'1\',
            \'timestamp\' => \'1470430358\',
            \'uuid\' => \'58182f09-be93-4026-8bfa-73440ad194d2\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'1125\',
            \'height\' => \'1500\',
          ),
        ),
      ),
      \'field_icon\' => array(
        \'und\' => array(
          array(
            \'fid\' => \'273\',
            \'uid\' => \'1\',
            \'filename\' => \'time_traveler.png\',
            \'uri\' => \'public://time_traveler.png\',
            \'filemime\' => \'image/png\',
            \'filesize\' => \'3999\',
            \'status\' => \'1\',
            \'timestamp\' => \'1463761459\',
            \'uuid\' => \'5ad46482-51a4-49d4-ab08-8a7420cf8884\',
            \'rdf_mapping\' => array(),
            \'alt\' => \'\',
            \'title\' => \'\',
            \'width\' => \'76\',
            \'height\' => \'77\',
          ),
        ),
      ),
      \'field_intro\' => array(
        \'und\' => array(
          array(
            \'value\' => \'Take an amazing journey through time and space! Search for curiosities from around the globe, and don’t forget to bring that magical whatchamacallit to help along the way. \',
            \'format\' => NULL,
            \'safe_value\' => \'Take an amazing journey through time and space! Search for curiosities from around the globe, and don’t forget to bring that magical whatchamacallit to help along the way. \',
          ),
        ),
      ),
      \'field_journey_guide_cover_title\' => array(
        \'und\' => array(
          array(
            \'value\' => \'TIME-HOPPING JOURNEY\',
            \'format\' => NULL,
            \'safe_value\' => \'TIME-HOPPING JOURNEY\',
          ),
        ),
      ),
      \'field_prompt\' => array(
        \'und\' => array(
          array(
            \'value\' => \'72\',
            \'revision_id\' => \'72\',
          ),
          array(
            \'value\' => \'73\',
            \'revision_id\' => \'73\',
          ),
          array(
            \'value\' => \'74\',
            \'revision_id\' => \'74\',
          ),
          array(
            \'value\' => \'75\',
            \'revision_id\' => \'75\',
          ),
          array(
            \'value\' => \'76\',
            \'revision_id\' => \'76\',
          ),
        ),
      ),
      \'field_shape_face\' => array(),
      \'files\' => array(),
      \'language\' => \'und\',
      \'last_comment_name\' => NULL,
      \'last_comment_timestamp\' => NULL,
      \'last_comment_uid\' => \'1\',
      \'log\' => \'\',
      \'menu\' => NULL,
      \'name\' => \'ntrive\',
      \'nid\' => NULL,
      \'node_export_drupal_version\' => \'7\',
      \'path\' => NULL,
      \'picture\' => \'0\',
      \'promote\' => \'0\',
      \'rdf_mapping\' => array(
        \'rdftype\' => array(
          \'sioc:Item\',
          \'foaf:Document\',
        ),
        \'title\' => array(
          \'predicates\' => array(
            \'dc:title\',
          ),
        ),
        \'created\' => array(
          \'predicates\' => array(
            \'dc:date\',
            \'dc:created\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'changed\' => array(
          \'predicates\' => array(
            \'dc:modified\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
        \'body\' => array(
          \'predicates\' => array(
            \'content:encoded\',
          ),
        ),
        \'uid\' => array(
          \'predicates\' => array(
            \'sioc:has_creator\',
          ),
          \'type\' => \'rel\',
        ),
        \'name\' => array(
          \'predicates\' => array(
            \'foaf:name\',
          ),
        ),
        \'comment_count\' => array(
          \'predicates\' => array(
            \'sioc:num_replies\',
          ),
          \'datatype\' => \'xsd:integer\',
        ),
        \'last_activity\' => array(
          \'predicates\' => array(
            \'sioc:last_activity_date\',
          ),
          \'datatype\' => \'xsd:dateTime\',
          \'callback\' => \'date_iso8601\',
        ),
      ),
      \'revision_timestamp\' => NULL,
      \'revision_uid\' => \'53\',
      \'status\' => \'1\',
      \'sticky\' => \'0\',
      \'title\' => \'Time Travelers\',
      \'tnid\' => \'0\',
      \'translate\' => \'0\',
      \'type\' => \'aic_theme\',
      \'uid\' => \'53\',
      \'uuid\' => \'e7a2f67d-e043-4779-b9ea-192d589f35d1\',
      \'vid\' => NULL,
      \'vuuid\' => \'6a24e513-2481-4846-a4bf-15c32a845a3e\',
    ),
)',
);
  return $node_export;
}
