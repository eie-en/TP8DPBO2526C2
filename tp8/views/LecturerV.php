<?php

class LecturerV
{
    public function render($data, $editData = null)
    {
        $no = 1;
        $dataLecturer = null;

        foreach ($data as $val) {
            list($id, $name, $nidn, $phone, $join_date) = $val;
            $dataLecturer .= "<tr class='text-center align-middle'>
                        <td>" . $no++ . "</td>
                        <td>" . $name . "</td>
                        <td>" . $nidn . "</td>
                        <td>" . $phone . "</td>
                        <td>" . $join_date . "</td>
                        <td>
                        <a href='index.php?id_edit=" . $id . "' class='btn btn-warning mb-2 mb-md-0 mb-lg-0 mb-xl-0'>Edit</a>
                        <a href='index.php?id_hapus=" . $id . "' class='btn btn-danger' onclick=\"return confirm('Yakin ingin menghapus data dosen " . htmlspecialchars($name, ENT_QUOTES) . "?')\">Hapus</a>
                        </td>
                        </tr>";
        }

        if ($editData) {
            list($editId, $editName, $editNidn, $editPhone, $editJoinDate) = $editData;
            $formTitle = "Edit Dosen";
            $btnName = "update";
            $btnText = "Update";
            $btnClass = "btn btn-warning";
            $cancelBtn = "<a href='index.php' class='btn btn-secondary'>Batal</a>";
        } else {
            $editId = "";
            $editName = "";
            $editNidn = "";
            $editPhone = "";
            $editJoinDate = "";
            $formTitle = "Tambah Dosen";
            $btnName = "add";
            $btnText = "Add";
            $btnClass = "btn btn-primary";
            $cancelBtn = "";
        }

        $tpl = new Template("templates/index.html");
        $tpl->replace("JUDUL", "Dosen");
        $tpl->replace("FORM_TITLE", $formTitle);
        $tpl->replace("VAL_ID", $editId);
        $tpl->replace("VAL_NAME", $editName);
        $tpl->replace("VAL_NIDN", $editNidn);
        $tpl->replace("VAL_PHONE", $editPhone);
        $tpl->replace("VAL_JOIN_DATE", $editJoinDate);
        $tpl->replace("BTN_NAME", $btnName);
        $tpl->replace("BTN_CLASS", $btnClass);
        $tpl->replace("BTN_TEXT", $btnText);
        $tpl->replace("BTN_CANCEL", $cancelBtn);
        $tpl->replace("DATA_TABEL", $dataLecturer);
        $tpl->write();
    }
}