<?php
echo "this is my change in feature 1";
/**
 * Implements hook_entity_info().
 */
function demo_entity_info() {
  
  $info = array();
  
  $info['project'] = array(
    'label' => t('Project'),
    'base table' => 'demo_projects',
    'entity keys' => array(
      'id' => 'id',
      'label' => 'name',
    ),
    'entity class' => 'Entity',
    'controller class' => 'ProjectEntityController',
    'module' => 'demo',
  );
  
  return $info;
}

/**
 * Implements hook_menu()
 */
function demo_menu() {
  $items = array();
  
  $items['projects'] = array(
    'title' => 'Our projects demo',
    'page callback' => 'demo_projects',
    'access arguments' => array('access content'),
  );
  
  return $items;
}

/**
 * Callback function for our project entities test path
 */
function demo_projects() {
  
  
  $projects = entity_load('project', array(1, 2, 3));
  
  // Saving new entities 
  if (!isset($projects[3])) {
    $entity = entity_create('project', array('id' => 3));
    $entity->name = t('Spring House');
    $entity->description = t('Some more lipsum.');
    $entity->deadline = '1397501132';
    $entity->save();
  }
  
  // Listing entities
  $list = entity_view('project', $projects);
  
  $output = array();
  foreach ($list['project'] as $project) {
    $output[] = drupal_render($project);
  }
  
  return implode($output);

}

/**
   * Extending the EntityAPIController for the Project entity.
   */
  class ProjectEntityController extends EntityAPIController {
    
    public function buildContent($entity, $view_mode = 'full', $langcode = NULL, $content = array()) {
  
      $build = parent::buildContent($entity, $view_mode, $langcode, $content);
      
      // Our additions to the $build render array.
      $build['description'] = array(
        '#type' => 'markup',
        '#markup' => check_plain($entity->description),
        '#prefix' => '<div class="project-description">',
        '#suffix' => '</div>',
      );
      $build['deadline'] = array(
        '#type' => 'markup',
        '#markup' => date('d F, Y', check_plain($entity->deadline)),
        '#prefix' => '<p>Deadline: ',
        '#suffix' => '</p>',
      );
      
      return $build;
    
    }
    
  }

