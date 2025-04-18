import React, { useEffect, useRef, useState } from 'react';
import './FormCustomizer.scss';
import ButtonCustomizer from './ButtonCustomizer';

import { useSetting } from "../../../../contexts/SettingContext";

const FormCustomizer = (props) => {
    const [currentHoverOn, setCurrentHoverOn] = useState('');
    const [currentEditSection, setCurrentEditSection] = useState('');
    const buttonRef = useRef();

    const { setting } = useSetting();

    useEffect(() => {
        document.body.addEventListener("click", (event) => {
            if (! buttonRef?.current?.contains(event.target) ) {
                setCurrentHoverOn('');
                setCurrentEditSection('');
            }
        })
    }, [])
    return (
        <>
            <div className='fromcustomizer-wrapper'>
                <div className='wrapper-content'>
                    <div className='label-section'>
                        <input
                            ref={currentHoverOn === 'description' ? buttonRef : null}
                            className={currentHoverOn === 'description' && 'active'}
                            onClick={(e) => setCurrentHoverOn('description')}
                            onChange={(e) => props.onChange('alert_text', e.target.value ) }
                            value={setting.alert_text}
                        />
                    </div>
                    <div className='form-section'>
                        <div ref={currentHoverOn === 'email_input' ? buttonRef : null} className='input-section'>
                            <input
                                readOnly
                                onClick={(e) => setCurrentHoverOn('email_input')}
                                className={currentHoverOn === 'email_input' && 'active'}
                                type="email"
                                placeholder={setting.email_placeholder_text}
                            />

                            {currentHoverOn === 'email_input' && (
                                <>
                                    <div
                                        className='input-editor'
                                        onClick={(e) => setCurrentEditSection('text')}
                                    >
                                        <p>Edit</p><span><i className='admin-font adminLib-edit'></i></span>
                                    </div>
                                </>
                            )}

                            {
                                // Email input has select
                                currentHoverOn === 'email_input' &&
                                <>
                                    {
                                        // Text section has select
                                        currentEditSection === 'text' &&
                                        <div className='setting-wrapper'>
                                            <div className='setting-nav'>...</div>
                                            <button onClick={(e) => {
                                                e.preventDefault();
                                                setCurrentEditSection('');
                                                    
                                            }} className="wrapper-close"><i class="admin-font adminLib-cross"></i></button>
                                            <div className="setting-section-dev">
                                                <span class="label">Placeholder text</span>
                                                <div class="property-section">
                                                    <input
                                                        type="text"
                                                        value={setting.email_placeholder_text}
                                                        onChange={ (e)=> props.onChange('email_placeholder_text', e.target.value)} 
                                                    />
                                                </div>
                                            </div>
                                        </div>
                                    }
                                </>
                            }
                            
                        </div>
                        <div className='button-section'>
                            <ButtonCustomizer
                                text={props.buttonText || 'Submit'}
                                proSetting={props.proSetting}
                                setting={setting['customize_btn']}
                                onChange={(key, value, isRestoreDefaults=false) => {
                                    const previousSetting = setting['customize_btn'] || {};
                                    if (isRestoreDefaults) {
                                        props.onChange('customize_btn', value);
                                    } else {
                                        props.onChange('customize_btn', { ...previousSetting, [key]: value });
                                    }
                                }}
                            />
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
}

export default FormCustomizer
