

/////////////////////////////////////////////////////////////////////////////////////
//                                                                                 //
//                                                                                 //
//                             ver 1.1.0 2014/07/03                                //
//                                                                                 //
//                                                                                 //
/////////////////////////////////////////////////////////////////////////////////////


function syukkacheck()
{
	var judge =true;
	var count = 1;
	var id = "";
	var value_obj = "";
	var name = "";
	var num = "";
	var value_num = 0;
	var zaiko_num = 0;
	var value_4CODE = 0;
	var isnum = true;
	while(1)
	{
		id = "syukka_"+count;
		var obj = document.getElementById(id);
		isnum = true;
//		alert(id);
		if(!obj)
		{
//			alert('break');
			break;
		}
		value_obj = obj.value;
		if(value_obj.match(/[^0-9]+/)) 
		{
			window.alert('���l����͂��Ă��������B');
			obj.style.backgroundColor = '#ff0000';
			judge = false;
			isnum = false;
		}
		value_num = Number(value_obj) ;
		name = obj.name;
		var num_array = name.split('_');
		num = num_array[2];
		zaiko_num = Number(num);
		if(isnum == true)
		{
			if(value_obj != "")
			{
				if(value_obj != '0')
				{
					if(value_obj.charAt(0)  == '0')
					{
						window.alert('�������l����͂��Ă��������B');
						obj.style.backgroundColor = '#ff0000';
						judge = false;
					}
					else if(num != "-1" && zaiko_num < value_num)
					{
						window.alert('�������l����͂��Ă��������B');
						obj.style.backgroundColor = '#ff0000';
						judge = false;
					}
					else
					{
						obj.style.backgroundColor  = '';
					}
				}
				else
				{
					obj.style.backgroundColor  = '';
				}
			}
			else
			{
				obj.style.backgroundColor  = '';
			}
		}
        // 2018/06/29 �ǉ��Ή� ��
                var hason_id = "hason_"+count;
		var hason_obj = document.getElementById(hason_id);
		if(hason_obj)
		{
                    var hason_value_obj = hason_obj.value;
                    if(hason_value_obj.match(/[^0-9]+/)) 
                    {
                            window.alert('���l����͂��Ă��������B');
                            hason_obj.style.backgroundColor = '#ff0000';
                            judge = false;
                    }
                    else
                    {
                            hason_obj.style.backgroundColor  = ''; 
                    }
                }

        // 2018/06/29 �ǉ��Ή� ��
		count++;
//		if(count == 10)
//		{
//			break;
//		}
	}
	obj = document.getElementsByName('4CODE')[0];
	value_4CODE = obj.value;
	obj = document.getElementById('check_4CODE');
	if(value_4CODE == "")
	{
		window.alert('�����I�����Ă��������B');
		judge = false;
	}
	else if(value_4CODE != obj.value)
	{
		window.alert('�����I�����܂�����\���{�^�����������Ă��������B');
		judge = false;
	}
        // 2018/06/29 �ǉ��Ή� ��(�J�����_�[)
        obj = document.getElementById('form_start');
	if(obj.value == "")
	{
		window.alert('��Ɠ����w�肵�Ă��������B');
		obj.style.backgroundColor = '#ff0000';
		judge = false;
	}
        else
        {
                // ���t�`�F�b�N
                var judgeDate = true;

                var ymd = obj.value;
                // �܂��u/�v�Őؒf
                var splitYmd = ymd.split("/");
                
                // 3�ɕ������邩
                if( splitYmd.length != 3 )
                {
                    // �������Ȃ���΃G���[
                    judgeDate = false;
                }
                else
                {
                        // ymd�ɕ���
                        var y = splitYmd[0];
                        var m = splitYmd[1];
                        var d = splitYmd[2];

                        // ���t�ɕϊ��ł��邩
                        var date = new Date(y, m-1, d);
                        // ������v���Ȃ���΂����������̂Ƃ݂Ȃ�
                        var month = date.getMonth()+1;
                        if(m != month)
                        {
                                judgeDate = false;
                        }
                }
                if( judgeDate == false )
                {
                        window.alert('���������t���w�肵�Ă��������B');
                        obj.style.backgroundColor = '#ff0000';
                        judge = false;
                }
                else
                {
                        obj.style.backgroundColor  = '';
                }
                
        }
//	obj = document.getElementById('form_start_0');
//	if(obj.value == "")
//	{
//		window.alert('�N�x��I�����Ă��������B');
//		obj.style.backgroundColor = '#ff0000';
//		judge = false;
//	}
//	else
//	{
//		obj.style.backgroundColor  = '';
//	}
//	obj = document.getElementById('form_start_1');
//	if(obj.value == "")
//	{
//		window.alert('����I�����Ă��������B');
//		obj.style.backgroundColor = '#ff0000';
//		judge = false;
//	}
//	else
//	{
//		obj.style.backgroundColor  = '';
//	}
//	obj = document.getElementById('form_start_2');
//	if(obj.value == "")
//	{
//		window.alert('����I�����Ă��������B');
//		obj.style.backgroundColor = '#ff0000';
//		judge = false;
//	}
//	else
//	{
//		obj.style.backgroundColor  = '';
//	}
        // 2018/06/29 �ǉ��Ή� ��(�J�����_�[)
	if(judge == true )
	{
		document.form.submit();
	}
	return judge;

}


