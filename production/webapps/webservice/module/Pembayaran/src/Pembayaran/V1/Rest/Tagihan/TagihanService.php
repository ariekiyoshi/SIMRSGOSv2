<?php
namespace Pembayaran\V1\Rest\Tagihan;

use DBService\DatabaseService;
use Zend\Db\Sql\Select;
use DBService\System;
use Zend\json\Json;
use DBService\Service;
use Zend\Db\Sql\TableIdentifier;

use Pembayaran\V1\Rest\Diskon\DiskonService;
use Pembayaran\V1\Rest\DiskonDokter\DiskonDokterService;
use Pembayaran\V1\Rest\EDC\EDCService;
use Pembayaran\V1\Rest\PembayaranTagihan\PembayaranTagihanService;
use Pembayaran\V1\Rest\PenjaminTagihan\PenjaminTagihanService;
use Pembayaran\V1\Rest\PiutangPasien\PiutangPasienService;
use Pembayaran\V1\Rest\PiutangPerusahaan\PiutangPerusahaanService;
use Pembayaran\V1\Rest\Deposit\DepositService;
use Pembayaran\V1\Rest\TagihanPendaftaran\TagihanPendaftaranService;

use Pembayaran\V1\Rest\PengembalianDeposit\PengembalianDepositService;
use General\V1\Rest\Referensi\ReferensiService;
use Cetakan\V1\Rest\KwitansiPembayaran\KwitansiPembayaranService;
use Penjualan\V1\Rest\Penjualan\PenjualanService;
use General\V1\Rest\Pasien\PasienService;

use Pembayaran\V1\Rest\SubsidiTagihan\Service as SubsidiService;

class TagihanService extends Service
{
	private $diskon;
	private $diskondokter;
	private $edc;
	private $penjamintagihan;
	private $piutangpasien;
	private $piutangperusahaan;
	private $deposit;
	private $pengembaliandeposit;
	private $kwitansipembayaran;
	private $penjualan;
	private $pasien;
	private $subsiditagihan;
	private $tagihanpendaftaran;
	
	protected $references = array(
		'Diskon' => true,
		'DiskonDokter' => true,
		'EDC' => true,
		'PenjaminTagihan' => true,
		'PiutangPasien' => true,
		'PiutangPerusahaan' => true,
		'Deposit' => true,
		'PengembalianDeposit' => true,
		'KwitansiPembayaran' => true,
		'Penjualan' => true,
		'Pasien' => true,
		'SubsidiTagihan' => true,
		'TagihanPendaftaran' => true
	);
    
    public function __construct($includeReferences = true, $references = array()) {
        $this->table = DatabaseService::get('SIMpel')->get(new TableIdentifier("tagihan", "pembayaran"));
		$this->entity = new TagihanEntity();
		
		$this->setReferences($references);
		
		$this->includeReferences = $includeReferences;
		
		if($includeReferences) {
			if($this->references['Diskon']) $this->diskon = new DiskonService();
			if($this->references['DiskonDokter']) $this->diskondokter = new DiskonDokterService();
			if($this->references['EDC']) $this->edc = new EDCService();
			if($this->references['PenjaminTagihan']) $this->penjamintagihan = new PenjaminTagihanService();
			if($this->references['PiutangPasien']) $this->piutangpasien = new PiutangPasienService();
			if($this->references['PiutangPerusahaan']) $this->piutangperusahaan = new PiutangPerusahaanService();
			if($this->references['Deposit']) $this->deposit = new DepositService();
			if($this->references['PengembalianDeposit']) $this->pengembaliandeposit = new PengembalianDepositService();
			if($this->references['KwitansiPembayaran']) $this->kwitansipembayaran = new KwitansiPembayaranService();			
			if($this->references['Penjualan']) $this->penjualan = new PenjualanService(true, array(
				'PenjualanDetil' => true,
				'TotalTagihan' => false
			));
			if($this->references['Pasien']) $this->pasien = new PasienService(false);
			if($this->references['SubsidiTagihan']) $this->subsiditagihan = new SubsidiService();
			if($this->references['TagihanPendaftaran']) $this->tagihanpendaftaran = new TagihanPendaftaranService(true, array(
				'Pendaftaran' => true,
				'Tagihan' => false
			));
		}
    }
    
	public function simpan($data) {
		$data = is_array($data) ? $data : (array) $data;
		$this->entity->exchangeArray($data);
		$this->table->insert($this->entity->getArrayCopy());
		
		return array(
			'success' => true
		);
	}
	
	public function reStoreTagihan($tagihan) {
	    $adapter = $this->table->getAdapter();
	    $stmt = $adapter->query('CALL pembayaran.reStoreTagihan(?)');
	    $results = $stmt->execute(array($tagihan));
	    $results->getResource()->closeCursor();
	    return array(
	        'success' => true
	    );
	}
	
	public function reDistribusiTagihan($tagihan) {
	    $adapter = $this->table->getAdapter();
	    $stmt = $adapter->query('CALL pembayaran.reProsesDistribusiTarif(?)');
	    $results = $stmt->execute(array($tagihan));
	    $results->getResource()->closeCursor();
	    return array(
	        'success' => true
	    );
	}
	
	public function load($params = array(), $columns = array('*'), $orders = array()) {
		$data = parent::load($params, $columns, $orders);

		if($this->includeReferences) {
			foreach($data as &$entity) {
				if($this->references['Diskon']) {
					$diskon = $this->diskon->load(array('TAGIHAN' => $entity['ID'], 'STATUS' => 1));
					if(count($diskon) > 0) $entity['REFERENSI']['DISKON'] = $diskon;
				}
				
				if($this->references['DiskonDokter']) {
					$diskondokter = $this->diskondokter->load(array('TAGIHAN' => $entity['ID'], 'STATUS' => 1));
					if(count($diskondokter) > 0) $entity['REFERENSI']['DISKON_DOKTER'] = $diskondokter;
				}
				
				if($this->references['EDC']) {
					$edc = $this->edc->load(array('TAGIHAN' => $entity['ID'], 'STATUS' => 1));
					if(count($edc) > 0) $entity['REFERENSI']['EDC'] = $edc;
				}

				if($this->references['PenjaminTagihan']) {
					$penjamintagihan = $this->penjamintagihan->load(array('TAGIHAN' => $entity['ID']));
					if(count($penjamintagihan) > 0) $entity['REFERENSI']['PENJAMIN_TAGIHAN'] = $penjamintagihan;
				}

				if($this->references['PiutangPasien']) {	
					$piutangpasien = $this->piutangpasien->load(array('TAGIHAN' => $entity['ID'], 'STATUS' => 1));
					if(count($piutangpasien) > 0) $entity['REFERENSI']['PIUTANG_PASIEN'] = $piutangpasien;
				}
				
				if($this->references['PiutangPerusahaan']) {
					$piutangperusahaan = $this->piutangperusahaan->load(array('TAGIHAN' => $entity['ID'], 'STATUS' => 1));
					if(count($piutangperusahaan) > 0) $entity['REFERENSI']['PIUTANG_PERUSAHAAN'] = $piutangperusahaan;
				}
				
				if($this->references['Deposit']) {
					$deposit = $this->deposit->load(array('TAGIHAN' => $entity['ID'], 'STATUS' => 1));
					if(count($deposit) > 0) $entity['REFERENSI']['DEPOSIT'] = $deposit;
				}
				
				if($this->references['PengembalianDeposit']) {
					$pengembaliandeposit = $this->pengembaliandeposit->load(array('TAGIHAN' => $entity['ID'], 'STATUS' => 1));
					if(count($pengembaliandeposit) > 0) $entity['REFERENSI']['PENGEMBALIAN_DEPOSIT'] = $pengembaliandeposit;
				}
				
				if($this->references['KwitansiPembayaran']) {
					$kwitansipembayaran = $this->kwitansipembayaran->load(array('TAGIHAN' => $entity['ID'], 'TUNAI' => 1, 'start'=>0, 'limit'=>1), array('*'), array('TANGGAL DESC'));
					if(count($kwitansipembayaran) > 0) $entity['REFERENSI']['KWITANSI_PEMBAYARAN'] = $kwitansipembayaran;
				}
				
				if($this->references['Penjualan']) {
					$penjualan = $this->penjualan->load(array('NOMOR' => $entity['ID']));
					if(count($penjualan) > 0) $entity['REFERENSI']['PENJUALAN'] = $penjualan;
				}
				
				if($this->references['Pasien']) {
					if($entity['JENIS'] == 1) {
						$pasien = $this->pasien->load(array('NORM' => $entity['REF']));
						if(count($pasien) > 0) $entity['REFERENSI']['PASIEN'] = $pasien[0];
					}
				}
				
				if($this->references['SubsidiTagihan']) {
					$subsiditagihan = $this->subsiditagihan->load(array('TAGIHAN' => $entity['ID']));
					if(count($subsiditagihan) > 0) $entity['REFERENSI']['SUBSIDI_TAGIHAN'] = $subsiditagihan;
				}
				
				if($this->references['TagihanPendaftaran']) {	
					if(is_object($this->references['TagihanPendaftaran'])) {
						$references = isset($this->references['TagihanPendaftaran']->REFERENSI) ? (array) $this->references['TagihanPendaftaran']->REFERENSI : array();
						$this->tagihanpendaftaran->setReferences($references, true);
						if(isset($this->references['TagihanPendaftaran']->COLUMNS)) $this->tagihanpendaftaran->setColumns((array) $this->references['TagihanPendaftaran']->COLUMNS);
					}
					$tagihanpendaftaran = $this->tagihanpendaftaran->load(array('TAGIHAN' => $entity['ID']));
					if(count($tagihanpendaftaran) > 0) $entity['REFERENSI']['TAGIHAN_PENDAFTARAN'] = $tagihanpendaftaran;
				}
			}
		}
		
		return $data;
	}
	
	public function getStatus($tagihan) {
		$data = $this->load(array('ID' => $tagihan));
		if(count($data) > 0) {
			return $data[0]['STATUS'];
		}
		return -1;
	}
}