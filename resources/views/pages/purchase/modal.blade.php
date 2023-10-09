 @foreach ($purchases as $purchase)
     @foreach ($purchase->purchasedetail as $purchaseDetail)
         @isset($purchaseDetail->id)
             <div class="modal fade" id="edit-{{ $purchaseDetail->id }}" tabindex="-1" aria-hidden="true">
                 <div class="modal-dialog modal-lg modal-dialog-centered1 modal-simple modal-add-new-cc">
                     <div class="modal-content p-3 p-md-5">
                         <div class="modal-body">
                             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                             <div class="text-center mb-4">
                                 <h3>Edit Purchase</h3>
                                 <p>Silahkan mengedit Purchase yang diinginkan</p>
                             </div>
                             <form class="row g-3" action="{{ route('purchase.update', $purchaseDetail->id) }}"
                                 method="POST">
                                 @csrf
                                 @method('PUT')
                                 <div class="col-12">
                                     <label class="form-label w-100">Nama Barang</label>
                                     <select class="form-select select2 form-control" data-allow-clear="true"
                                         name="inventory_id" required>
                                         @foreach ($inventories as $inventory)
                                             <option value="{{ $inventory->id }}"
                                                 {{ $inventory->id === $purchaseDetail->inventory_id ? 'selected' : '' }}>
                                                 {{ $inventory->name }}</option>
                                         @endforeach
                                     </select>
                                 </div>
                                 <div class="col-12">
                                     <label class="form-label w-100">Jumlah Barang</label>
                                     <input name="qty" class="form-control" type="number"
                                         value="{{ $purchaseDetail->qty }}" required />
                                 </div>
                                 <div class="col-12 text-center">
                                     <button type="submit" class="btn btn-primary me-sm-3 me-1 mt-3">Submit</button>
                                     <button type="reset" class="btn btn-label-secondary btn-reset mt-3"
                                         data-bs-dismiss="modal" aria-label="Close">
                                         Cancel
                                     </button>
                                 </div>
                             </form>
                         </div>
                     </div>
                 </div>
             </div>

             <!-- Modal Delete -->
             <div class="modal fade" id="hapus-{{ $purchaseDetail->id }}" tabindex="-1" aria-labelledby="exampleModalLabel"
                 aria-hidden="true">
                 <div class="modal-dialog" role="document">
                     <div class="modal-content">
                         <div class="modal-header">
                             <h5 class="modal-title" id="exampleModalLabel">Hapus Barang</h5>
                             <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                             </button>
                         </div>
                         <form action="{{ route('purchase.destroy', $purchaseDetail->id) }}" method="post">
                             @method('DELETE')
                             @csrf
                             <input type="hidden" name="id" id="id" value="{{ $purchaseDetail->id }}">
                             <div class="modal-body">
                                 Anda yakin ingin menghapus barang <b>{{ $purchaseDetail->number }}</b>
                                 ini ?
                             </div>
                             <div class="modal-footer">
                                 <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                     <i class="bx bx-x d-block d-sm-none"></i>
                                     <span class="d-none d-sm-block">Tutup</span>
                                 </button>
                                 <button type="submit" class="btn btn-outline-danger ml-1" id="btn-save">
                                     <i class="bx bx-check d-block d-sm-none"></i>
                                     <span class="d-none d-sm-block">Yakin</span>
                                 </button>
                             </div>
                         </form>
                     </div>
                 </div>
             </div>
         @endisset
     @endforeach
 @endforeach
