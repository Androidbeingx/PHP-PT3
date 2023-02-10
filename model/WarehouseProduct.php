<?php

namespace proven\store\model;

class WarehouseProduct {

 

    public function __construct(
            private int $warehouse_id = 0,
            private int $product_id = 0,
            private ?int $stock = 0
    ) {
        
    }

    //Get
    public function getWarehouseId(): int {
        return $this->warehouse_id;
    }

    public function getProductId(): int {
        return $this->product_id;
    }

    public function getStock(): ?int {
        return $this->stock;
    }

    //Set
    public function setWarehoseId(int $warehose_id): void {
        $this->warehose_id = $warehouse_id;
    }

    public function setPrductId(?string $product_id): void {
        $this->product_id = $product_id;
    }

    public function setStock(?string $stock): void {
        $this->stock = $stock;
    }

    public function __toString() {
        return sprintf("WarehouseProduct{[warehouse_id=%d][product_id=%d][stock=%d]}",
                $this->warehouse_id, $this->product_id, $this->stock);
    }

}
