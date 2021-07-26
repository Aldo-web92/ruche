<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\FileUploader;
use App\Service\Statistiques;

class ProcessController extends AbstractController
{    
	
	/**
     * @Route("/upload", name="process")
     */
    public function upload(Request $request, FileUploader $fileUploader, Statistiques $statistiques): Response
    {		
		$lutin_file = $request->get("factory");
		$upload_dir = $this->getParameter('kernel.project_dir').'/public/uploads/';
		
		if($lutin_file)
		{
			$lutin_file_name = $fileUploader->upload($lutin_file);
			
			$file = fopen($upload_dir.$lutin_file_name, "r");
			
			if($file)
			{
				$stock = new Stock();
				$stock->setFileName($lutin_file_name);
				$stock->setDate(new \DateTime("now"));
				
				$qtity_stock = 0;
				
				while(!feof($file))
				{
					$buffer = fgets($file);
					$exploded_data = explode(",",$buffer);
					
					foreach($exploded_data as $data)
					{
						if(count($data) == 8)
						{			
							$entityManager = $this->getDoctrine()->getManager();
							
							$receiver = new Receiver();													
								
							if($data[5] && preg_match('#^[A-Za-z0-9]{8}[-]{1}[A-Za-z0-9]{4}[-]{1}[A-Za-z0-9]{8}[-]{1}[A-Za-z0-9]{8}[-]{1}[A-Za-z0-9]{12}$#',$data[5]))
							{
								$receiver->setUuid($data[5]);
							}	
							
							if($data[6] && preg_match('#^[A-Za-z-]{1.}$#',$data[6]))
							{
								$receiver->setFirstName($data[6]);
							}

							if($data[7] && preg_match('#^[A-Za-z-]{1.}$#',$data[7]))
							{
								$receiver->setLastName($data[7]);
							}	

							if($data[8] && preg_match('#^[A-Za-z-]{1.}$#',$data[8]))
							{
								$receiver->setCountryCode($data[8]);
							}		
							
							$receiver->save();
							
							$entityManager->persist($receiver);
							
							$gift = new Gift();
							
							if($data[0] && preg_match('#^[A-Za-z0-9]{8}[-]{1}[A-Za-z0-9]{4}[-]{1}[A-Za-z0-9]{8}[-]{1}[A-Za-z0-9]{8}[-]{1}[A-Za-z0-9]{12}$#',$data[0]))
							{
								$gift->setUuid($data[0]);
							}	
							
							if($data[1] && preg_match('#^[A-Za-z-]{1.}$#',$data[1]))
							{
								$gift->setCode($data[1]);
							}
							
							if($data[2] && preg_match('#^[A-Za-z-]{15}$#',$data[2]))
							{
								$gift->setDescription($data[2]);
							}
							
							if($data[3] && is_float($data[3]) || is_int($data[3])))
							{
								$gift->setPrice($data[3]);
							}
							
							$gift->setReceiver($receiver);
							
							$entityManager->persist($gift);
														
						}	
					}
					
					$qtity_stock +=1;
					
				}
				
				$stock->setQuantity($qtity_stock);
				$entityManager->persist($stock);
				$entityManager->flush();
				
				fclose($file);
				
			}							
			
			$quantity_gift = $statistiques->QuantityGift($lutin_file);
			
			$min_price_gift = $statistiques->MinPrice($lutin_file);
			
			$max_price_gift = $statistiques->MaxPrice($lutin_file);
			
			$average_price_gift = $statistiques->AveragePrice($lutin_file); 
			
			$number_country = $statistiques->NumberCountry($lutin_file);
			
			return $this->json([
            'message' => 'Votre fichier a bien été sauvegardé', 
			'file_name' => $lutin_file_name,
			'gift' => 'Le nombre de cadeau est de'.$quantity_gift,
			'min_price' => 'Le plus petit prix est de'.$min_price_gift.' €',
			'max_price' => 'Le plus grand prix est de'.$max_price_gift.' €',
			'average_price' => 'Le prix moyen est de'.$average_price_gift.' €',
			'number_country' => 'Il y a '.$number_country.' différents'
			]);
			
		}
		else
		{
			return $this->json([
            'message' => 'Veuillez envoyer un fichier correct',            
			]);
			
		}
		     
    }
}
