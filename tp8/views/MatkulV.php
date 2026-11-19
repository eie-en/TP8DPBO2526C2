<?php

class MatkulV
{
    public function render($data, $editData = null)
    {
        $no = 1;
        $dataMatkul = null;
        $dataDosen = null;

        foreach ($data['matkul'] as $val) {
            list($id, $nama_matkul, $jumlah_sks, $nama_dosen) = $val;
            $dataMatkul .= "<tr class='text-center align-middle'>
                        <td>".$no++.'</td>
                        <td>'.$nama_matkul.'</td>
                        <td>'.$jumlah_sks.'</td>
                        <td>'.$nama_dosen."</td>
                        <td>
                        <a href='matkul.php?id_edit=".$id."' class='btn btn-warning mb-2 mb-md-0 mb-lg-0 mb-xl-0'>Edit</a>
                        <a href='matkul.php?id_hapus=".$id."' class='btn btn-danger' onclick=\"return confirm('Yakin ingin menghapus mata kuliah ".htmlspecialchars($nama_matkul, ENT_QUOTES)."?')\">Hapus</a>
                        </td>
                        </tr>";
        }

        $selectedDosen = $editData ? $editData['dosen'] : '';
        foreach ($data['dosen'] as $val) {
            list($id, $nama, $nidn, $phone, $join_date) = $val;
            $selected = ($id == $selectedDosen) ? 'selected' : '';
            $dataDosen .= "<option value='".$id."' ".$selected.'>'.$nama.'</option>';
        }

        if ($editData) {
            list($editId, $editNamaMatkul, $editSks) = $editData;
            $formTitle = 'Edit Mata Kuliah';
            $btnName = 'update';
            $btnText = 'Update';
            $btnClass = 'btn btn-warning';
            $cancelBtn = "<a href='matkul.php' class='btn btn-secondary'>Batal</a>";
        } else {
            $editId = '';
            $editNamaMatkul = '';
            $editSks = '';
            $formTitle = 'Tambah Mata Kuliah';
            $btnName = 'add';
            $btnText = 'Add';
            $btnClass = 'btn btn-primary';
            $cancelBtn = '';
        }

        $tpl = new Template('templates/matkul.html');
        $tpl->replace('JUDUL', 'Mata Kuliah');
        $tpl->replace('FORM_TITLE', $formTitle);
        $tpl->replace('VAL_ID', $editId);
        $tpl->replace('VAL_NAMA_MATKUL', $editNamaMatkul);
        $tpl->replace('VAL_SKS', $editSks);
        $tpl->replace('OPTION', $dataDosen);
        $tpl->replace('BTN_NAME', $btnName);
        $tpl->replace('BTN_CLASS', $btnClass);
        $tpl->replace('BTN_TEXT', $btnText);
        $tpl->replace('BTN_CANCEL', $cancelBtn);
        $tpl->replace('DATA_TABEL', $dataMatkul);
        $tpl->write();
    }
}
