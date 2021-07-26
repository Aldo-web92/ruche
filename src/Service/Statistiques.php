<?php

namespace App\Service;

use App\Entity\Stock;

class Statistiques
{   

    public function QuantityGift($file_name)
    {
		
		$stock = $this->getDoctrine()
            ->getRepository(Stock::class)
            ->findBy([
                'file_name' => $file_name
            ]);		        

        return $stock->getQuantity();
    }

    public function MinPrice($file_name)
    {
		
		$stock = $this->getDoctrine()
            ->getRepository(Stock::class)
            ->findBy([
                'file_name' => $file_name
            ]);
		
		$tab_price = arrray();
		
		foreach($stock-<getGifts() as $gift)
		{
			$tab_price[] = $gift->getPrice();
		}
		
		$min_price = min($tab_price);
		
		return $min_price;
		
	}
	
	public function MaxPrice($file_name)
    {
		
		$stock = $this->getDoctrine()
            ->getRepository(Stock::class)
            ->findBy([
                'file_name' => $file_name
            ]);
		
		$tab_price = arrray();
		
		foreach($stock-<getGifts() as $gift)
		{
			$tab_price[] = $gift->getPrice();
		}
		
		$max_price = max($tab_price);
		
		return $max_price;
		
	}
	
	public function AveragePrice($file_name)
    {
		
		$stock = $this->getDoctrine()
            ->getRepository(Stock::class)
            ->findBy([
                'file_name' => $file_name
            ]);
		
		$tab_price = arrray();
		
		foreach($stock-<getGifts() as $gift)
		{
			$tab_price[] = $gift->getPrice();
		}
		
		$sum_price = array_sum($tab_price);
		
		$average_price = $sum_price / count($tab_price);
		
		return $average_price;
		
	}
	
	public function NumberCountry($file_name)
    {
		
		$stock = $this->getDoctrine()
            ->getRepository(Stock::class)
            ->findBy([
                'file_name' => $file_name
            ]);
		
		$tab_country = arrray();
		
		foreach($stock-<getGifts() as $gift)
		{
			
			if (array_key_exists($gift->getReceiver()->getCountryCode(), $tab_country)) 
			{
				$tab_country[$gift->getReceiver()->getCountryCode()] += 1;
			}
			else
			{	
				$tab_country[$gift->getReceiver()->getCountryCode()] = 1;
			}
		}
		
		$number_country = count($tab_country);
		
		return $number_country;
		
	}		
	
}