<?php

namespace QuetzalStudio\SnapBi\IntrabankTransfer;

use QuetzalStudio\SnapBi\Amount;
use QuetzalStudio\SnapBi\Contracts\ServicePayload;
use QuetzalStudio\SnapBi\Contracts\AdditionalInfo;

class Payload implements ServicePayload
{
    public function __construct(
        public string $partnerReferenceNo,
        public Amount $amount,
        public string $beneficiaryAccountNo,
        public string $sourceAccountNo,
        public string $transactionDate,
        public ?string $beneficiaryEmail = null,
        public ?string $currency = null,
        public ?string $customerReference = null,
        public ?string $feeType = null,
        public ?string $remark = null,
        public AdditionalInfo|array|null $additionalInfo = null,
    ) {}

    public function toArray(): array
    {
        $data = [
            'partnerReferenceNo' => $this->partnerReferenceNo,
            'amount' => $this->amount->toArray(),
            'beneficiaryAccountNo' => $this->beneficiaryAccountNo,
            'sourceAccountNo' => $this->sourceAccountNo,
            'transactionDate' => $this->transactionDate,
            'beneficiaryEmail' => $this->beneficiaryEmail,
            'currency' => $this->currency,
            'customerReference' => $this->customerReference,
            'feeType' => $this->feeType,
            'remark' => $this->remark,
        ];

        if ($this->additionalInfo) {
            $data['additionalInfo'] = $this->additionalInfo instanceof AdditionalInfo
                ? $this->additionalInfo->toArray()
                : $this->additionalInfo;
        }

        foreach ($data as $key => $val) {
            if (is_null($val)) {
                unset($data[$key]);
            }
        }

        return $data;
    }
}
