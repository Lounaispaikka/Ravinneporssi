<?php include_once("engine/rp.start.php");

$_GET["contractid"] = intval($_GET["contractid"]);

if ($_GET["contractid"]>0 && $_SESSION["clientID"]) {

	if (rpGetContract($_GET["contractid"], "added_clientid")==$_SESSION["clientID"] || strstr(rpGetContract($_GET["contractid"], "to_clientid"),"[".intval($_SESSION["clientID"])."]")) {
		
		require_once("fpdf/fpdf.php");
		
		$pdf = new FPDF("P","mm","A4");
		$pdf->AddPage();
		$pdf->SetFont("Arial","B",22);
		$pdf->Cell(40,10,utf8_decode("Sopimus lannan luovutuksesta tai vastaanotosta"));
		
		$pdf->Ln(12);
		
		$pdf->SetTextColor(110,184,76);
		$pdf->SetFont("Arial","B",18);
		$pdf->Cell(40,10,utf8_decode("Lannan luovuttaja"));
		$pdf->SetTextColor(0,0,0);
		
		$pdf->Ln(10);
				
		// cell 1 start
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Luovuttajan nimi"));
		
		$pdf->SetX(100);
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Luovuttajan osoite"));
		
		$pdf->Ln(5);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode(rpGetContract($_GET["contractid"], "output_name")));
		
		$pdf->SetX(100);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode(rpGetContract($_GET["contractid"], "output_address")));
		
		$pdf->Ln(8);
		
		// cell 1 end
		
		// cell 2 start
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Luovuttajan puhelinnumero"));
		
		$pdf->SetX(100);
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Luovuttajan sähköpostiosoite"));
		
		$pdf->Ln(5);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode(rpGetContract($_GET["contractid"], "output_phonenumber")));
		
		$pdf->SetX(100);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode(rpGetContract($_GET["contractid"], "output_email")));
		
		$pdf->Ln(8);
		
		// cell 2 end
		
		$pdf->Ln(2);
		
		$pdf->SetTextColor(110,184,76);
		$pdf->SetFont("Arial","B",18);
		$pdf->Cell(40,10,utf8_decode("Tilan tiedot"));
		$pdf->SetTextColor(0,0,0);
		
		$pdf->Ln(10);
		
		// cell 3 start
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Tilatunnus"));
		
		$pdf->SetX(100);
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Eläinyksikkömäärä (ey)"));
		
		$pdf->Ln(5);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode(rpGetContract($_GET["contractid"], "output_companycode")));
		
		$pdf->SetX(100);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode(rpGetContract($_GET["contractid"], "output_animal_amount")));
		
		$pdf->Ln(8);
		
		// cell 3 end
		
		// cell 4 start
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Hallinnassa oleva peltoala (ha)"));
		
		$pdf->SetX(100);
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Muut lannanluovutussopimukset (ha)"));
		
		$pdf->Ln(5);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode(rpGetContract($_GET["contractid"], "output_field_area")));
		
		$pdf->SetX(100);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode(rpGetContract($_GET["contractid"], "output_other_contracts")));
		
		$pdf->Ln(8);
		
		// cell 4 end
		
		// cell 5 start
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Eläinyksikkömäärä yhteensä (ey/ha)"));

		$pdf->Ln(5);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode(rpGetContract($_GET["contractid"], "output_total_animal_amount")));
		
		$pdf->Ln(8);
		
		// cell 5 end
		
		$pdf->Ln(2);
		
		$pdf->SetTextColor(110,184,76);
		$pdf->SetFont("Arial","B",18);
		$pdf->Cell(40,10,utf8_decode("Eläinlajit"));
		$pdf->SetTextColor(0,0,0);
		
		$pdf->Ln(10);
		
		$animal_array = explode("|end|",rpGetContractAnimals("[rp(title)]|end|", $_GET["contractid"], false));
		
		foreach($animal_array as $value) {
			
			if ($value != "") {
				
				$pdf->SetFont("Arial","",12);
				$pdf->Cell(40,10,utf8_decode($value));
		
				$pdf->Ln(5);
				
			}	
			
		}
		
		$pdf->Ln(6);
		
		$pdf->SetTextColor(110,184,76);
		$pdf->SetFont("Arial","B",18);
		$pdf->Cell(40,10,utf8_decode("Lannan vastaanottaja"));
		$pdf->SetTextColor(0,0,0);
		
		$pdf->Ln(10);
		
		// cell 6 start
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Nimi"));
		
		$pdf->SetX(100);
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Osoite"));
		
		$pdf->Ln(5);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode(rpGetContract($_GET["contractid"], "input_name")));
		
		$pdf->SetX(100);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode(rpGetContract($_GET["contractid"], "input_address")));
		
		$pdf->Ln(8);
		
		// cell 6 end
		
		// cell 7 start
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Tila-/Y-tunnus"));
		
		$pdf->SetX(100);
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Puhelinnumero"));
		
		$pdf->Ln(5);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode(rpGetContract($_GET["contractid"], "input_bic")));
		
		$pdf->SetX(100);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode(rpGetContract($_GET["contractid"], "input_phonenumber")));
		
		$pdf->Ln(8);
		
		// cell 7 end
		
		// cell 8 start
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Sähköpostiosoite"));
		
		$pdf->Ln(5);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode(rpGetContract($_GET["contractid"], "input_email")));
		
		$pdf->Ln(8);
		
		// cell 8 end
		
		$pdf->Ln(2);
		
		$pdf->SetTextColor(110,184,76);
		$pdf->SetFont("Arial","B",18);
		$pdf->Cell(40,10,utf8_decode("Luovutettavat lantalajit ja määrät"));
		$pdf->SetTextColor(0,0,0);
		
		$pdf->Ln(10);
		
		$products_array = explode("|end|",rpGetContractProducts("[rp(title)]|[rp(amount)]|[rp(distance)]|end|", $_GET["contractid"], false));
		
		foreach($products_array as $value) {
			
			if ($value != "") {
				
				$product_array = explode("|",$value);
				
				$pdf->SetFont("Arial","B",14);
				$pdf->Cell(40,10,utf8_decode($product_array[0]));
				
				$pdf->Ln(8);
				
				$pdf->SetFont("Arial","B",12);
				$pdf->Cell(40,10,utf8_decode("Lannan määrä (m3 / vuosi)"));
				
				$pdf->SetX(100);
				
				$pdf->SetFont("Arial","B",12);
				$pdf->Cell(40,10,utf8_decode("Lannan kuljetusmatka (km)"));
				
				$pdf->Ln(5);
				
				$pdf->SetFont("Arial","",12);
				$pdf->Cell(40,10,utf8_decode($product_array[1]));
				
				$pdf->SetX(100);
				
				$pdf->SetFont("Arial","",12);
				$pdf->Cell(40,10,utf8_decode($product_array[2]));
				
				$pdf->Ln(8);
				
			}	
			
		}
		
		$pdf->Ln(2);
		
		$pdf->SetTextColor(110,184,76);
		$pdf->SetFont("Arial","B",18);
		$pdf->Cell(40,10,utf8_decode("Sopimuksen tiedot"));
		$pdf->SetTextColor(0,0,0);
		
		$pdf->Ln(10);
		
		// cell 9 start
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Lannan levitysala vastaanottajalle (ha)"));
		
		$pdf->SetX(100);
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Lanta vastaanotetaan jatkojalostettavaksi"));
		
		$pdf->Ln(5);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode(rpGetContract($_GET["contractid"], "input_dung_area")));
		
		$pdf->SetX(100);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode(rpBooleanConvert(rpGetContract($_GET["contractid"], "input_refinement"))));
		
		$pdf->Ln(8);
		
		// cell 9 end
		
		// cell 10 start
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Sopimusaika"));
		
		$pdf->SetX(100);
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Lannan kuljettaa"));
		
		$pdf->Ln(5);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode(rpGetContract($_GET["contractid"], "input_contract_time")));
		
		$pdf->SetX(100);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode(rpGetContract($_GET["contractid"], "transporter")));
		
		$pdf->Ln(8);
		
		// cell 10 end
		
		// cell 11 start
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Lannan levittää"));
		
		$pdf->SetX(100);
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Kuljetuskulut maksaa"));
		
		$pdf->Ln(5);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode(rpGetContract($_GET["contractid"], "distributor")));
		
		$pdf->SetX(100);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode(rpGetContract($_GET["contractid"], "transportation_payer")));
		
		$pdf->Ln(8);
		
		// cell 11 end
		
		// cell 12 start
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Levityskulut maksaa"));
		
		$pdf->Ln(5);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode(rpGetContract($_GET["contractid"], "distribution_payer")));
		
		$pdf->Ln(8);
		
		// cell 12 end
				
		$pdf->Ln(2);
		
		$pdf->SetTextColor(110,184,76);
		$pdf->SetFont("Arial","B",18);
		$pdf->Cell(40,10,utf8_decode("Muut sopimusehdot"));
		$pdf->SetTextColor(0,0,0);
		
		$pdf->Ln(12);
		
		$pdf->SetFont("Arial","",12);
		$pdf->MultiCell(180,6,utf8_decode(rpGetContract($_GET["contractid"], "remarks")),0,L);
		
		$pdf->Ln(6);
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Paikka ja aika"));
		
		$pdf->Ln(5);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode(rpGetContract($_GET["contractid"], "place_and_time")));

		$pdf->Ln(12);
		$pdf->MultiCell(180,6,utf8_decode("Tästä sopimuksesta on tehty kaksi samansisältöistä kappaletta; yksi luovuttajalle ja yksi vastaanottajalle."),0,L);
		
		$pdf->Ln(4);
		
		$pdf->SetTextColor(110,184,76);
		$pdf->SetFont("Arial","B",18);
		$pdf->Cell(40,10,utf8_decode("Allekirjoitukset"));
		$pdf->SetTextColor(0,0,0);
		
		$pdf->Ln(12);
		
		// cell 13 start
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Lannan luovuttaja"));
		
		$pdf->SetX(100);
		
		$pdf->SetFont("Arial","B",12);
		$pdf->Cell(40,10,utf8_decode("Lannan vastaanottaja"));
		
		$pdf->Ln(14);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode("___________________________________"));
		
		$pdf->SetX(100);
		
		$pdf->SetFont("Arial","",12);
		$pdf->Cell(40,10,utf8_decode("___________________________________"));
		
		$pdf->Ln(8);
		
		// cell 13 end
		
		$pdf->Output();
		
	} else {echo "Sinulla ei ole oikeuksia tähän sopimukseen.";}
	
}

include_once("engine/rp.end.php"); ?>