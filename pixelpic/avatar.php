<?php
	try {
		
		require_once 'pixelpicConfig.php';
		require_once 'imageFactory.php';
		require_once 'pixelpicDraw.php';
		require_once 'pixelpicDatabase.php';
		require_once 'pixelpicList.php';
		require_once 'pixelpicConst.php';
		require_once 'pixelpicFigure.php';
		require_once 'pixelpic.php';
		require_once 'pixelpicDBSQL.php';
		require_once 'pixelpicColorFactory.php';
		require_once 'pixelpicColorPaletteFactory.php';
		require_once 'pixelpicRandom.php';
		require_once 'pixelpicParamIn.php';

		$ParamIn->Get();
		$figure = new PixelpicFigure();
		$figure->SetElement(lsBody, $PixelpicDBSQL->GetRand(lsBody));

		$figure->SetElement(lsHead, $PixelpicDBSQL->GetRandByCategory(lsHead, $figure->GetElement(lsBody)->pic->GetCategory()));
		$figure->SetElement(lsLArm, $PixelpicDBSQL->GetRandByCategory(lsLArm, $figure->GetElement(lsBody)->pic->GetCategory()));
		$figure->SetElement(lsRArm, $PixelpicDBSQL->GetRandByGroupCategory(lsRArm, $figure->GetElement(lsLArm)->pic->GetGroup(), $figure->GetElement(lsLArm)->pic->GetCategory() ));
		$figure->SetElement(lsLLeg, $PixelpicDBSQL->GetRandByCategory(lsLLeg, $figure->GetElement(lsBody)->pic->GetCategory()));
		$figure->SetElement(lsRLeg, $PixelpicDBSQL->GetRandByGroupCategory(lsRLeg, $figure->GetElement(lsLLeg)->pic->GetGroup(), $figure->GetElement(lsLArm)->pic->GetCategory() ));

		$figure->SetColors( $ColorPaletteFactory->GetRandomColors(6) );

		$figure->GetFinalSize($imgWidth, $imgHeight);
		$imgInput = $ImageFactory->CreateTransparentImage($imgWidth, $imgHeight);

		$pixelDraw = new PixelDraw($imgInput);

		$figure->SetPositionsToCenter($pixelDraw->GetWidth(), $pixelDraw->GetWidth());
		$figure->Draw($pixelDraw);

		if ($RandomFactory->GetRandom(0, 1) == 0)
			imageflip($imgInput, IMG_FLIP_HORIZONTAL);

		$imgOutput = $ImageFactory->CreateOutputImage($imgInput, $pixelPicConfig->outputWidth, $pixelPicConfig->outputHeight);
		imagedestroy($imgOutput);
		imagedestroy($imgInput);

	} catch (Exception $e) {
		if ($pixelPicConfig->printErrorsInOutput) {
			echo '<h1>Error!</h1><br />Exception: ', nl2br($e);
		}
	}

?>
