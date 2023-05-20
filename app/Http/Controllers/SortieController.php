<?php

namespace App\Http\Controllers;
use App\Models\Sortie;
use App\Models\Beneficiaire;
use App\Models\Detaitsortie;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use DB;
class SortieController extends Controller
{
    // index page
    public function index()
    {
        $beneficiaire = DB::table('beneficiaires')->get();
        $Sorties = Sortie::orderBy('idsortie','DESC')
        ->paginate(50);
        
        
        return view('sortie.list',compact('Sorties','beneficiaire'));
       
    }



    public function select(){
        $produits = DB::table('produits')
        ->join('stock', 'produits.idproduit', '=', 'stock.produit_id')
        ->select('produits.*', 'stock.quantiter')->where('stock.quantiter','>',0)
        ->get();
        
        $beneficiaire = Beneficiaire::join('etablissements', 'beneficiaires.etablissement_id', '=', 'etablissements.idetablissement')->orderBy('idbeneficiaire','DESC')->paginate(50);
        return view('sortie.create',compact('beneficiaire','produits'));

    }


        public function save(Request $request)
        {
            
       
        // $messages = [    'quantities.required' => '.', 
        //       'quantities.min' => 'Le quantité doit être plus que :min.',
        //           'quantities.max' => 'Le quantité doit être moins que la quantite disponible :max.',];


        //           $validator = Validator::make($request->all(),[
        //             'fournisseur' =>'required',
        //             'reception' =>'required',
        //             'quantities' =>'required',
        //             'produitsSelected' =>'required',
                   
        //         ],$messages);
        //         if ($validator->fails()) {
        //             return redirect()->back()->withErrors($validator)->withInput();
        //         }
        
        $validator = Validator::make($request->all(),[
            'beneficiaire' =>'required',
            'sortie' =>'required',
            'numsortie' => 'required',
            'quantities' =>'required',
            'produitsSelected' =>'required',
           
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->with('error','Veulliez remplir toutes les informations !')->withInput();
                 }
        else{
         $quantities = $request->input('quantities');
     

        
      
         $produitsSelected = $request->input('produitsSelected');
         $quantities = $request->input('quantities');
  
         // Calculate the sum of quantities
         $nbrArticles = count($produitsSelected);
         
        // return with errrors
       // return redirect()->route('stock.index')->withErrors($validator)->withInput();
       $Sortie=new Sortie();
       $Sortie->nombeneficiaire  = $request->beneficiaire;
       $Sortie->datesortie = $request->sortie;
       $Sortie->numsortie = $request->numsortie;
       $Sortie->nbr_article_sortie =$nbrArticles;
       $Sortie->save();

       $lastid = Sortie::latest()->first()-> idsortie;
   
       
     
        
       
       foreach ($request->input('produitsSelected', []) as $produitId) {
        $quantity = $request->input('quantities.' . $produitId, 0);
        
        // Insert the product and its quantity into the detailSortie table
        DB::table('detailsortie')->insert([
            'produit_id' => $produitId,
            'sortie_id' => $lastid,
            'quantite' => $quantity
        ]);

         // Update the stock table with the new quantity
    DB::table('stock')
    ->where('produit_id', $produitId)
    ->decrement('quantiter', $quantity);
    }
   


       return redirect()->route('sortie.index')->with('success', 'Sortie enregistré avec succes.');
    }
}



            
            // Attach the selected products and their quantities to the Sortie record
          //  foreach ($selectedProducts as $produitId) {
          //      $Sortie->produits()->attach($produitId, ['quantite' => $quantities[$produitId]]);
          //  }
            
            // Redirect to the Sorties index page with a success message
            
       

        public function edit(){}
        public function destroy($idsortie, Request $request)
        {
            $Sortie = Sortie::findOrFail($idsortie);
            
            // Delete the related detailreception entries
            Detaitsortie::where('sortie_id', $idsortie)->delete();
            
            $Sortie->delete();
            
            return redirect()->route('sortie.index')->with('success', 'Sortie supprimée avec succes.');
        }

    }       
        


      
    


