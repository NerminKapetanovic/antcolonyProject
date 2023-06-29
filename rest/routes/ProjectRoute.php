<?php

/**
 * @OA\Get(path="/projects", tags={"projects"}, security={{"ApiKeyAuth": {}}},
 *         summary="Return all projects from the API. ",
 *         @OA\Parameter(in="query", name="search", description="Get projects"),
 *         @OA\Response( response=200, description="List of notes.")
 * )
 */
Flight::route('GET /projects', function(){
    Flight::json(Flight::ProjectService()->get_all());
  });


  /**
 * @OA\Get(path="/projects/{id}", tags={"projects"}, security={{"ApiKeyAuth": {}}},
 *     @OA\Parameter(in="path", name="id", example=1, description="Getting by project ID"),
 *     @OA\Response(response="200", description="Get individual project by ID")
 * )
 */

  Flight::route('GET /projects/@id', function($id){
    Flight::json(Flight::ProjectService()->get_by_id($id));
  });
  


  /**
* @OA\Post(
*     path="/projects", security={{"ApiKeyAuth": {}}},
*     description="Add project",
*     tags={"projects"},
*     @OA\RequestBody(description="Some kind of project", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*    				@OA\Property(property="name", type="string", example="test",	description="project title"),
*    				@OA\Property(property="description", type="string", example="test",	description="project description" ),
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="project has been created"
*     ),
*     @OA\Response(
*         response=500,
*         description="Error"
*     )
* )
*/
  Flight::route('POST /projects', function(){
    Flight::json(Flight::ProjectService()->add(Flight::request()->data->getData()));
  });
  
 


  /**
* @OA\Put(
*     path="/projects/{id}", security={{"ApiKeyAuth": {}}},
*     description="Update project",
*     tags={"projects"},
*     @OA\Parameter(in="path", name="id", example=1, description="Note ID"),
*     @OA\RequestBody(description="Basic note info", required=true,
*       @OA\MediaType(mediaType="application/json",
*    			@OA\Schema(
*    				@OA\Property(property="name", type="string", example="test",	description="project title"),
*    				@OA\Property(property="description", type="string", example="test",	description="project description" ),
*           @OA\Property(property="color", type="string", example="white",	description="white, red, blue, ..." ),
*        )
*     )),
*     @OA\Response(
*         response=200,
*         description="project that has been updated"
*     ),
*     @OA\Response(
*         response=500,
*         description="Error"
*     )
* )
*/
  Flight::route('PUT /projects/@id', function($id){
    $data = Flight::request()->data->getData();
    Flight::json(Flight::ProjectService()->update($id, $data));
  });
  



  /**
* @OA\Delete(
*     path="/projects/{id}", security={{"ApiKeyAuth": {}}},
*     description="Deleting project",
*     tags={"projects"},
*     @OA\Parameter(in="path", name="id", example=1, description="project ID"),
*     @OA\Response(
*         response=200,
*         description="project deleted"
*     ),
*     @OA\Response(
*         response=500,
*         description="Error"
*     )
* )
*/
  Flight::route('DELETE /projects/@id', function($id){
    Flight::ProjectService()->delete($id);
    Flight::json(["message" => "deleted"]);
  });
  

  
  ?>