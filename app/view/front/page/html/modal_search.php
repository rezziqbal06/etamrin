<style>
#modal-search {
  background: rgb(255, 255, 255);
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.896796218487395) 0%, rgba(226, 226, 226, 0.8743872549019608) 100%);
}

#isearch {
  border: none;
}

#modal-content {
  background-color: transparent;
  border: none;
}

card-search {
  box-shadow: 0px 8px 20px rgb(0 0 0 / 6%);
  border-radius: 8px;
  padding: 16px;
  background-color: #fff;
}
</style>
<div class="modal fade p-1" id="modal-search" aria-hidden="true" aria-labelledby="modal_search">
  <div class="modal-dialog ">
    <div class="modal-content" id="modal-content">
      <div class="modal-header" style="border:none">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="container">
          <div class="row card-search">
            <form action="" id="fsearch">
              <div class="form-group m-1">
                <div class="input-group">
                  <input type="text" class="form-control" id="isearch" aria-describedby="cariPekerjaan" placeholder="Cari Pekerjaan" required>
                  <button type="submit" class="btn"><i class="fa fa-search"></i></button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
